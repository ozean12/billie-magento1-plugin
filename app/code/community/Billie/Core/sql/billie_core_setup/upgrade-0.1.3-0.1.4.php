<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
UPDATE eav_attribute SET is_required = 1 WHERE attribute_code = 'company';
");

$installer->endSetup();