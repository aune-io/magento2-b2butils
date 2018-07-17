<?php

namespace Aune\B2bUtils\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;

class Data
{
    const XML_PATH_REGISTRATION_REQUIRE_APPROVAL = 'customer/create_account/ab_require_approval';
    const XML_PATH_CATALOG_PRICE_RESERVED = 'catalog/price/ab_reserved';
    
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * Return wether customer approval is required or not
     *
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
    public function isCustomerApprovalRequired($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_REGISTRATION_REQUIRE_APPROVAL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    /**
     * Return wether catalog prices are reserved to registered users or not
     *
     * @param \Magento\Store\Model\Store|int|string $store
     * @return string|null
     */
    public function isCatalogPriceReserved($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CATALOG_PRICE_RESERVED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
    
    /**
     * Return whether the customer has been approved or not
     */
    public function isCustomerApproved($customer)
    {
        if ($customer instanceof \Magento\Customer\Api\Data\CustomerInterface) {
            $attribute = $customer->getCustomAttribute(ApprovalStatus::ATTRIBUTE_CODE);
            if ($attribute instanceof \Magento\Framework\Api\AttributeValue) {
                return $attribute->getValue() == ApprovalStatus::STATUS_APPROVED;
            }
            return false;
        } elseif ($customer instanceof \Magento\Customer\Model\Customer) {
            return $customer->getData(ApprovalStatus::ATTRIBUTE_CODE) == ApprovalStatus::STATUS_APPROVED;
        }
        
        return false;
    }
}
