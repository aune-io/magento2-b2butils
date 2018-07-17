<?php

namespace Aune\B2bUtils\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

use Aune\B2bUtils\Helper\Data as HelperData;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;

class CustomerAuthenticated implements ObserverInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;

    public function __construct(HelperData $helperData)
    {
        $this->helperData = $helperData;
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
        
        $customer = $observer->getEvent()->getModel();
        if($customer->getCustomAttribute(ApprovalStatus::ATTRIBUTE_CODE) == ApprovalStatus::STATUS_APPROVED) {
            return $this;
        }

        throw new LocalizedException(
            __('Your account is not approved for login.')
        );
    }
}
