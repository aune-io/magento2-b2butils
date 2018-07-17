<?php

namespace Aune\B2bUtils\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
 
    /**
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }
    
    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
 
        // Add approval status attribute
        $customerSetup->addAttribute(
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
                'adminhtml_only' => 1,
            ]
        );
        
        // Assign attribute to forms
		$attribute = $customerSetup->getEavConfig()->getAttribute(
		    \Magento\Customer\Model\Customer::ENTITY,
		    ApprovalStatus::ATTRIBUTE_CODE
	    );
	    $attribute->setData(
			'used_in_forms',
			['adminhtml_customer']
		);
        
        // Add approval email attribute
        $customerSetup->addAttribute(
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
                'adminhtml_only' => 1,
            ]
        );
		
		$attribute->save();
    }
}
