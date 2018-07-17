<?php

namespace Aune\B2bUtils\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\ResourceModel\Customer as CustomerResource;

use Aune\B2bUtils\Helper\Data as HelperData;
use Aune\B2bUtils\Model\EmailNotification;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;

class CustomerSaveAfter implements ObserverInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;
    
    /**
     * @var EmailNotification
     */
    protected $emailNotification;
    
    /**
     * @var CustomerResource
     */
    protected $customerResource;

    public function __construct(
        HelperData $helperData,
        EmailNotification $emailNotification,
        CustomerResource $customerResource
    ) {
        $this->helperData = $helperData;
        $this->emailNotification = $emailNotification;
        $this->customerResource = $customerResource;
    }

    /**
     * Check that the customer has been approved after login
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->helperData->isCustomerApprovalRequired()) {
            return $this;
        }

        $customer = $observer->getEvent()->getCustomerDataObject();
        $status = $customer->getCustomAttribute(ApprovalStatus::ATTRIBUTE_CODE);
        $emailStatus = $customer->getCustomAttribute(ApprovalStatus::ATTRIBUTE_CODE_EMAIL);
        $status = $status ? $status->getValue() : false;
        $emailStatus = $emailStatus ? $emailStatus->getValue() : false;

        // Skip if status is pending or the notification has already been sent
        if($status == $emailStatus || !in_array($status, [
            ApprovalStatus::STATUS_APPROVED,
            ApprovalStatus::STATUS_REJECTED,
        ])) {
            return $this;
        }

        // Send notification to customer
        if($status == ApprovalStatus::STATUS_APPROVED) {
            $this->emailNotification->abCustomerApproved($customer);
        } elseif($status == ApprovalStatus::STATUS_REJECTED) {
            $this->emailNotification->abCustomerRejected($customer);
        }
        
        // Save email status
        $object = new \Magento\Framework\DataObject([
            'id' => $customer->getId(),
            'entity_id' => $customer->getId(),
            ApprovalStatus::ATTRIBUTE_CODE_EMAIL => $status,
        ]);

        $this->customerResource->saveAttribute($object, ApprovalStatus::ATTRIBUTE_CODE_EMAIL);
    }
}
