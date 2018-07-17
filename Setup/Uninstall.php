<?php

namespace Aune\B2bUtils\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Aune\B2bUtils\Model\Entity\Attribute\Source\ApprovalStatus;
 
class Uninstall implements UninstallInterface
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
 
    /**
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
 
        $eavSetup = $this->eavSetupFactory->create();
        
        $eavSetup->removeAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            ApprovalStatus::ATTRIBUTE_CODE
        );
        $eavSetup->removeAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            ApprovalStatus::ATTRIBUTE_CODE_EMAIL
        );
        
        $setup->endSetup();
    }
}
