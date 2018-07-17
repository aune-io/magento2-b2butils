<?php

namespace Aune\B2bUtils\Model;

use Magento\Customer\Api\Data\CustomerInterface;

interface EmailNotificationInterface
{
    /**
     * Send notification of new customer registration
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function abNewCustomerNotification(CustomerInterface $customer);

    /**
     * Send notification customer approval
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function abCustomerApproved(CustomerInterface $customer);
    
    /**
     * Send notification customer rejection
     *
     * @param CustomerInterface $customer
     * @return void
     */
    public function abCustomerRejected(CustomerInterface $customer);
}
