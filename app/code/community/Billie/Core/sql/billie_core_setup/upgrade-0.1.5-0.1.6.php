<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_duration` INTEGER ( 3 ) NOT NULL;
");

$installer->endSetup();