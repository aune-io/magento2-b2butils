<?php

namespace Aune\B2bUtils\Plugin\Catalog\Pricing\Renderer;

use Magento\Customer\Model\Session;
use Aune\B2bUtils\Helper\Data as HelperData;

class FinalPriceBox
{
    /**
     * @var Session
     */
    protected $session;
    
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @param Session $session
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
     */
    public function aroundToHtml(
        \Magento\Catalog\Pricing\Render\FinalPriceBox $subject,
        \Closure $proceed
    ) {
        if($this->helperData->isCatalogPriceReserved() && !$this->session->isLoggedIn()) {
            return '';
        }

        return $proceed();
    }
}
