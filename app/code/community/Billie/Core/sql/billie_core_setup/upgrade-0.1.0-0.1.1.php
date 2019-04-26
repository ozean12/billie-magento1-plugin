<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$installer->getTable('sales/order')}` 
ADD `billie_reference_id` VARCHAR( 255 ) NOT NULL;
");

$installer->endSetup();