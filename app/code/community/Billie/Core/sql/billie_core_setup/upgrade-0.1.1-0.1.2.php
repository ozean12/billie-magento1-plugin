<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_viban` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_vbic` VARCHAR( 255 ) NOT NULL;
");

$installer->endSetup();