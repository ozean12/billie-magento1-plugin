<?php

class Billie_Core_Block_Adminhtml_Transaction_Log_View extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup      = 'billie_core';
        $this->_controller      = 'adminhtml_transaction_log';
        $this->_mode            = 'view';

        parent::_construct();

    }

    protected function _getHelper(){
        return Mage::helper('billie_core');
    }

    protected function _getModel(){
        return Mage::registry('current_transactionEntry');
    }

    public function getHeaderText()
    {

           return $this->_getHelper()->__("New");
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }

}
