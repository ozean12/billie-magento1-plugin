<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `billie_legal_form` VARCHAR( 255 ) NOT NULL,
ADD `billie_industry_sector` VARCHAR( 255 ) NOT NULL;
  
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `billie_legal_form` VARCHAR( 255 ) NOT NULL,
ADD `billie_industry_sector` VARCHAR( 255 ) NOT NULL;
");

$installer->endSetup();