<?php

namespace Aune\B2bUtils\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

use Aune\B2bUtils\Helper\Data as HelperData;
use Aune\B2bUtils\Model\EmailNotification;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;

class CustomerRegisterSuccess implements ObserverInterface
{
    /**
     * @var HelperData
     */
    private $helperData;
    
    /**
     * @var EmailNotification
     */
    private $emailNotification;

    /**
     * @param HelperData $helperData
     * @param EmailNotification $emailNotification
     */
    public function __construct(
        HelperData $helperData,
        EmailNotification $emailNotification
    ) {
        $this->helperData = $helperData;
        $this->emailNotification = $emailNotification;
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
            return;
        }
        
        $this->emailNotification->abNewCustomerNotification(
            $observer->getCustomer()
        );
    }
}
