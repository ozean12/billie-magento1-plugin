<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('billie_core/transaction_log'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Log Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Store Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Order Id')
    ->addColumn('reference_id', Varien_Db_Ddl_Table::TYPE_VARCHAR,64,array(
        'nullable'  => false,
    ),'Billie Reference Ids')
    ->addColumn('transaction_tstamp', Varien_Db_Ddl_Table::TYPE_VARCHAR,64,array(
        'nullable'  => true,
    ), 'transaction at')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIME, null,
        array('default' => Varien_Db_Ddl_Table::TIMESTAMP_INIT_UPDATE), 'created at')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Customer Id')
    ->addColumn('mode', Varien_Db_Ddl_Table::TYPE_VARCHAR,12,array(
        'nullable'  => true,
    ), 'transaction mode')
    ->addColumn('billie_state', Varien_Db_Ddl_Table::TYPE_VARCHAR,12,array(
        'nullable'  => true,
    ), 'billie state')
    ->addColumn('request', Varien_Db_Ddl_Table::TYPE_TEXT,array(
        'nullable'  => false,
    ), 'transaction at')
    ->addIndex($installer->getIdxName('billie_core/transaction_log', array('customer_id')),
        array('customer_id'))
    ->addForeignKey($installer->getFkName('billie_core/transaction_log', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE);
$installer->getConnection()->createTable($table);

$installer->endSetup();