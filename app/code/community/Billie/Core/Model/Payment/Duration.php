<?php


class Billie_Core_Model_Payment_Duration extends Mage_Core_Model_Config_Data
{

    public function save()
    {
        $duration = $this->getValue();

        if (!ctype_digit($duration)) {

            Mage::throwException(Mage::Helper('billie_core')->__('The input must be numeric only'));

        } else if ($duration > 120) {
            Mage::throwException(Mage::Helper('billie_core')->__('The input must be between 0 and 120'));

        }

        return parent::save();
    }

}