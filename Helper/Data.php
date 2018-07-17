<?php

namespace Aune\B2bUtils\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

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
}
