<?php

class Billie_Core_Block_Payment_Form_Payafterdelivery extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('billie/core/payment/form/payafterdelivery.phtml');
    }
}
