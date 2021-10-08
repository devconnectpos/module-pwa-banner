<?php
/**
 * Created by PhpStorm.
 * User: xuantung
 * Date: 10/4/18
 * Time: 3:22 PM
 */

namespace SM\PWABanner\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend_Db_Exception;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->addPWABannerTable($setup);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param OutputInterface      $output
     *
     * @throws Zend_Db_Exception
     */
    public function execute(SchemaSetupInterface $setup, OutputInterface $output)
    {
        $output->writeln('  |__ Create PWA banner table');
        $this->addPWABannerTable($setup);
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @throws Zend_Db_Exception
     */
    protected function addPWABannerTable(SchemaSetupInterface $setup)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('sm_pwa_banner');

        if ($setup->getConnection()->isTableExists($tableName)) {
            $setup->endSetup();

            return;
        }

        $table = $setup->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'banner_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true,
                ],
                'ID'
            )
            ->addColumn(
                'banner_url',
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Banner URL'
            )
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false],
                'Is Active'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Modified'
            )
            ->addColumn(
                'store',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Store Ids'
            )
            ->setComment('PWA Banner');
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
