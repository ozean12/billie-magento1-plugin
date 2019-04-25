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


}