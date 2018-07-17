<?php

namespace Aune\B2bUtils\Plugin\Catalog\Pricing\Renderer;

use Magento\Customer\Model\Session;
use Aune\B2bUtils\Helper\Data as HelperData;

class FinalPriceBox
{
    /**
     * @var Session
     */
    private $session;
    
    /**
     * @var HelperData
     */
    private $helperData;

    /**
     * @param Session $session
     * @param HelperData $helperData
     */
    public function __construct(
        Session $session,
        HelperData $helperData
    ) {
        $this->session = $session;
        $this->helperData = $helperData;
    }
    
    /**
     * Do not print the block if customer is not logged in and catalog price is reserved
     * 
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundToHtml(
        \Magento\Catalog\Pricing\Render\FinalPriceBox $subject,
        \Closure $proceed
    ) {
        if(!$this->helperData->isCatalogPriceReserved() || $this->session->isLoggedIn()) {
            return $proceed();
        }

        return '';
    }
}
