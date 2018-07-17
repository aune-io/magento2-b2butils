<?php

namespace Aune\B2bUtils\Model\Entity\Attribute\Source;

class ApprovalStatus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const ATTRIBUTE_CODE = 'ab_approval_status';
    const ATTRIBUTE_CODE_EMAIL = 'ab_approval_email';
    
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    
    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        return [
            ['value' => self::STATUS_PENDING, 'label' => 'Pending or not needed'],
            ['value' => self::STATUS_APPROVED, 'label' => 'Approved'],
            ['value' => self::STATUS_REJECTED, 'label' => 'Rejected'],
        ];
    }

    /**
     * Retrieve option array
     *
     * @return array
     */
    public function getOptionArray()
    {
        return [
            self::STATUS_PENDING => 'Pending or not needed',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }
}
