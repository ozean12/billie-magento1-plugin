<?php

class Billie_Core_Block_Adminhtml_Transaction_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'billie_core';
        $this->_controller = 'adminhtml_transaction_log';
        $this->_headerText = Mage::helper('billie_core')->__('Billie Transaction Log');

        parent::__construct();
        $this->_removeButton('add');
    }


}

