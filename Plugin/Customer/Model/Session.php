<?php

namespace Aune\B2bUtils\Plugin\Customer\Model;

use Aune\B2bUtils\Helper\Data as HelperData;

class Session
{
    /**
     * @var HelperData
     */
    private $helperData;

    /**
     * @param HelperData $helperData
     */
    public function __construct(
        HelperData $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * Prevent user authentication on registration if confirmation is needed
     *
     * @param \Magento\Customer\Model\Session $subject
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     */
    public function aroundSetCustomerDataAsLoggedIn(
        \Magento\Customer\Model\Session $subject,
        \Closure $proceed,
        \Magento\Customer\Api\Data\CustomerInterface $customer
    ) {
        if(!$this->helperData->isCustomerApprovalRequired() ||
            $this->helperData->isCustomerApproved($customer)) {
            return $proceed($customer);
        }
        
        return $subject;
    }
}
