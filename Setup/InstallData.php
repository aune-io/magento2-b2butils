<?php

namespace Aune\B2bUtils\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var Config
     */
    private $eavConfig;
    
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        Config $eavConfig,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
    }
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
 
        // Add approval status attribute
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            ApprovalStatus::ATTRIBUTE_CODE,
            [
                'type' => 'int',
                'label' => 'Approval Status',
                'input' => 'select',
                'source' => 'Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus',
                'required' => false,
                'default' => 0,
                'system' => false,
                'position' => 120,
                'sort_order' => 120,
            ]
        );
        
        // Assign attribute to forms
		$attribute = $this->eavConfig->getAttribute(
		    \Magento\Customer\Model\Customer::ENTITY,
		    ApprovalStatus::ATTRIBUTE_CODE
	    );
	    $attribute->setData(
			'used_in_forms',
			['adminhtml_customer']
		);
        
        // Add approval email attribute
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            ApprovalStatus::ATTRIBUTE_CODE_EMAIL,
            [
                'type' => 'int',
                'label' => 'Approval Status Email',
                'input' => 'select',
                'source' => 'Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus',
                'required' => false,
                'default' => 0,
                'system' => false,
                'position' => 125,
                'sort_order' => 125,
            ]
        );
		
		$attribute->save();
    }
}
