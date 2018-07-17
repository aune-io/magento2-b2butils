<?php

namespace Aune\B2bUtils\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Store\Model\StoreManagerInterface;

class EmailNotification extends \Magento\Customer\Model\EmailNotification
    implements EmailNotificationInterface
{
    /**#@+
     * Configuration paths for email templates and identities
     */
    const XML_PATH_REGISTRATION_NOTIFICATION_RECIPIENT = 'customer/create_account/ab_notification_email_recipients';

    const XML_PATH_REGISTRATION_NOTIFICATION_EMAIL_TEMPLATE = 'customer/create_account/ab_notification_email_template';

    const XML_PATH_CUSTOMER_APPROVED_EMAIL_TEMPLATE = 'customer/create_account/ab_approved_email_template';

    const XML_PATH_CUSTOMER_REJECTED_EMAIL_TEMPLATE = 'customer/create_account/ab_rejected_email_template';
    /**#@-*/
    
    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param CustomerRegistry $customerRegistry
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $transportBuilder
     * @param CustomerViewHelper $customerViewHelper
     * @param DataObjectProcessor $dataProcessor
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        CustomerRegistry $customerRegistry,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        CustomerViewHelper $customerViewHelper,
        DataObjectProcessor $dataProcessor,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->customerRegistry = $customerRegistry;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        
        parent::__construct($customerRegistry, $storeManager, $transportBuilder, $customerViewHelper, $dataProcessor, $scopeConfig);
    }
    
    /**
     * Send notification of new customer registration
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function abNewCustomerNotification(CustomerInterface $customer)
    {
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }
        $store = $this->storeManager->getStore($customer->getStoreId());

        $emails = $this->scopeConfig->getValue(
            self::XML_PATH_REGISTRATION_NOTIFICATION_RECIPIENT,
            'store',
            $store
        );

        if(!$emails) {
            return;
        }
        
        $recipients = array_map(function($email) {
            return ['email' => $email, 'name' => ''];
        }, explode(',', $emails));

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_REGISTRATION_NOTIFICATION_EMAIL_TEMPLATE,
            ['customer' => $this->getFullCustomerObject($customer), 'store' => $store],
            $storeId,
            $recipients
        );
    }

    /**
     * Send notification customer approval
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function abCustomerApproved(CustomerInterface $customer)
    {
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }
        $store = $this->storeManager->getStore($customer->getStoreId());

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_CUSTOMER_APPROVED_EMAIL_TEMPLATE,
            ['customer' => $this->getFullCustomerObject($customer), 'store' => $store],
            $storeId
        );
    }
    
    /**
     * Send notification customer rejection
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function abCustomerRejected(CustomerInterface $customer)
    {
        $storeId = $customer->getStoreId();
        if (!$storeId) {
            $storeId = $this->getWebsiteStoreId($customer);
        }
        $store = $this->storeManager->getStore($customer->getStoreId());

        $this->sendEmailTemplate(
            $customer,
            self::XML_PATH_CUSTOMER_REJECTED_EMAIL_TEMPLATE,
            ['customer' => $this->getFullCustomerObject($customer), 'store' => $store],
            $storeId
        );
    }
    
    /**
     * Send corresponding email template
     *
     * @param CustomerInterface $customer
     * @param string $template configuration path of email template
     * @param array $templateParams
     * @param int|null $storeId
     * @param array $recipients
     * 
     * @return void
     */
    private function sendEmailTemplate(
        $customer,
        $template,
        $templateParams = [],
        $storeId = null,
        $recipients = []
    ) {
        $sender = $this->scopeConfig->getValue(
            self::XML_PATH_REGISTER_EMAIL_IDENTITY,
            'store',
            $storeId
        );
        $templateId = $this->scopeConfig->getValue($template, 'store', $storeId);
        
        if (!$recipients) {
            $recipients = [[
                'email' => $customer->getEmail(),
                'name' => $this->customerViewHelper->getCustomerName($customer),
            ]];
        }

        $transportBuilder = $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
            ->setTemplateVars($templateParams)
            ->setFrom($sender);

        foreach($recipients as $recipient) {
            $transportBuilder->addTo($recipient['email'], $recipient['name']);
        }

        $transportBuilder->getTransport()
            ->sendMessage();
    }

    /**
     * Get either first store ID from a set website or the provided as default
     *
     * @param CustomerInterface $customer
     * @param int|string|null $defaultStoreId
     * @return int
     */
    private function getWebsiteStoreId($customer, $defaultStoreId = null)
    {
        if ($customer->getWebsiteId() != 0 && empty($defaultStoreId)) {
            $storeIds = $this->storeManager->getWebsite($customer->getWebsiteId())->getStoreIds();
            reset($storeIds);
            $defaultStoreId = current($storeIds);
        }
        return $defaultStoreId;
    }
    
    /**
     * Create an object with data merged from Customer and CustomerSecure
     *
     * @param CustomerInterface $customer
     * @return \Magento\Customer\Model\Data\CustomerSecure
     */
    private function getFullCustomerObject($customer)
    {
        // No need to flatten the custom attributes or nested objects since the only usage is for email templates and
        // object passed for events
        $mergedCustomerData = $this->customerRegistry->retrieveSecureData($customer->getId());
        $customerData = $this->dataProcessor
            ->buildOutputDataArray($customer, \Magento\Customer\Api\Data\CustomerInterface::class);
        $mergedCustomerData->addData($customerData);
        $mergedCustomerData->setData('name', $this->customerViewHelper->getCustomerName($customer));
        return $mergedCustomerData;
    }
}
