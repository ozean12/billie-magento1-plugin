<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `billie_tax_id` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_tax_id` VARCHAR( 255 ) NOT NULL;
  
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `billie_registration_number` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_registration_number` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `billie_company` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_company` VARCHAR( 255 ) NOT NULL;

ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `billie_salutation` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_salutation` VARCHAR( 255 ) NOT NULL;
");

$installer->endSetup();