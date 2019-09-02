<?php

class Billie_Core_Block_Payment_Form_Payafterdelivery extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('billie/core/payment/form/payafterdelivery.phtml');
    }

    /**
     * Name from billing address in the format "Firstname Lastname".
     * @return string
     */
    public function getBillingName()
    {
        $quote = $this->getMethod()->getInfoInstance()->getQuote();
        $address = $quote->getBillingAddress();
        $billingName = $address->getFirstname() . ' ' . $address->getLastname();

        return $billingName;
    }

    public function getCompany(){

        $quote = $this->getMethod()->getInfoInstance()->getQuote();
        $address = $quote->getBillingAddress();
        $billingCompany = $address->getCompany();

        return $billingCompany;

    }

    /**
     * select prefix from billing address
     * @param $option
     * @return string
     */
    public function compareSalutation($option){

        $quote = $this->getMethod()->getInfoInstance()->getQuote();

        $address = $quote->getBillingAddress();
        $salutation = strtolower($address->getPrefix());
        $maleComparison = array('herr','male','mann','mister');
        $femaleComparison = array('frau','female','miss');
        $selectHtml = '';

        if(in_array($salutation,$maleComparison) && $option = 'male'){

            $selectHtml = 'checked="checked"';

        } else if(in_array($salutation,$femaleComparison) && $option = 'female'){

            $selectHtml = 'checked="checked"';

        }
        return $selectHtml;
    }
}
