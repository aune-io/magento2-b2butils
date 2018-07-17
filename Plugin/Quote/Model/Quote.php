<?php

namespace Aune\B2bUtils\Plugin\Quote\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\Session;
use Aune\B2bUtils\Helper\Data as HelperData;

class Quote
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
     * Prevent add to cart if customer is not logged in and catalog price is reserved
     *
     * @param \Magento\Quote\Model\Quote $subject
     * @param \Magento\Catalog\Model\Product $product
     * @param $request
     * @param $processMode
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeAddProduct(
        \Magento\Quote\Model\Quote $subject,
        \Magento\Catalog\Model\Product $product,
        $request = null,
        $processMode = \Magento\Catalog\Model\Product\Type\AbstractType::PROCESS_MODE_FULL
    ) {
        if($this->helperData->isCatalogPriceReserved() && !$this->session->isLoggedIn()) {
            throw new LocalizedException(__('Guest users are not allowed to add products to cart, please login or register to proceed.'));
        }
    }
}
