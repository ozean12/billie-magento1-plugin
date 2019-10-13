<?php

class Billie_Core_Model_Resource_Address
{
    public function toOptionArray()
    {

        $attributeSelect = array(array('value' => '', 'label' => 'none'));
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = 'SELECT eavt.entity_type_id, eav.* FROM ' . $resource->getTableName('eav/attribute') .' eav LEFT JOIN  ' . $resource->getTableName('eav/entity_type') .' eavt ON eav.entity_type_id = eavt.entity_type_id WHERE eavt.entity_type_code = "customer_address"' ;
        $results = $readConnection->fetchAll($query);


        foreach ($results as $attribute){

            $attributeSelect[] = array('value' => $attribute['attribute_code'], 'label' => ($attribute['attribute_code'] == 'street')?Mage::Helper('payment')->__('street 2. field'):$attribute['attribute_code']);

        }
        return $attributeSelect;

    }
}