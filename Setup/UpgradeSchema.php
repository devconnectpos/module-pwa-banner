<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 12/27/18
 * Time: 2:49 PM
 */

namespace SM\PWABanner\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '0.1.1', '<')) {
            $this->addBannerStoreColumn($setup, $context);
        }

        $installer->endSetup();
    }

    protected function addBannerStoreColumn(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();
        
        $installer->getConnection()->addColumn(
            $installer->getTable('sm_pwa_banner'),
            'store',
            [
                'type'    => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Store Ids',
            ]);
        
        $installer->endSetup();
    }
}
