<?php

class Billie_Core_Block_Adminhtml_Billie_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'billie_core';
        $this->_controller = 'adminhtml_billie_order';
        $this->_headerText = $this->__('Billie Order History');
        // $this->_addButtonLabel  = $this->__('Add Button Label');
        parent::__construct();
        $this->_removeButton('add');
    }

}
