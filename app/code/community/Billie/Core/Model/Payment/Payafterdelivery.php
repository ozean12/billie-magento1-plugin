<?php

class Billie_Core_Model_Payment_Payafterdelivery extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'payafterdelivery';
    protected $_formBlockType = 'billie_core/payment_form_payafterdelivery';
    protected $_infoBlockType = 'billie_core/payment_info_payafterdelivery';

    public function assignData($data)
    {
        $info = $this->getInfoInstance();

        if ($data->getBillieLegalForm()) {
            $info->setBillieLegalForm($data->getBillieLegalForm());
        }

        if ($data->getBillieIndustrySector()) {
            $info->setBillieIndustrySector($data->getBillieIndustrySector());
        }

        return $this;
    }

    public function validate()
    {
        parent::validate();
        $info = $this->getInfoInstance();

        if (!$info->getBillieLegalForm()) {
            $errorCode = 'invalid_data';
            $errorMsg = $this->_getHelper()->__("Legal form is a required field.\n");
        }

        if (!$info->getBillieIndustrySector()) {
            $errorCode = 'invalid_data';
            $errorMsg .= $this->_getHelper()->__('Industry sector is a required field.');
        }

        if ($errorMsg) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('billie/payment/redirect', array('_secure' => false));
    }
}