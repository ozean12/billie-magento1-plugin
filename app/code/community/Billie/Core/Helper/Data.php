<?php
 
class Billie_Core_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Receive all legal forms from SDK
     *
     * @return array
     */
    public function getAllLegalForms(){

        $allLegalForms = Billie\Util\LegalFormProvider::all();
        return $allLegalForms;

    }

    /**
     * Receive legal forms from SDK
     *
     * @param $code
     * @return string
     */
    public function getLegalFormByCode($code){

        $legalForm = Billie\Util\LegalFormProvider::get($code);
        return $legalForm['label'];

    }

    /**
     *
     *
     * @return mixed
     *
     */

    public function getCompany(){

        return $this->htmlEscape(Mage::getModel('checkout/cart')->getQuote()->getBillingAddress()->getCompany());

    }

    public function compareSalutation($option){

        $salutation = strtolower(Mage::getModel('checkout/cart')->getQuote()->getBillingAddress()->getPrefix());
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