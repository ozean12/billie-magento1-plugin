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

        if ($data->getBillieViban()) {
            $info->setBillieViban($data->getBillieViban());
        }

        if ($data->getBillieVatId()) {
            $info->setBillieVatId($data->getBillieVatId());
        }

        if ($data->getBillieRegistrationNumber()) {
            $info->setBillieRegistrationNumber($data->getBillieRegistrationNumber());
        }

        if ($data->getBillieCompany()) {
            $info->setBillieCompany($data->getBillieCompany());
        }

        if ($data->getBillieSalutation()) {
            $info->setBillieSalutation($data->getBillieSalutation());
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

        if ($errorMsg) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

}