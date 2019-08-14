<?php
class Billie_Core_Block_Adminhtml_Billie_Order_Renderer_BillieReferenceId extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $value =  $row->getData($this->getColumn()->getIndex());
        $order = Mage::getModel('sales/order')->load($value);

        return $order->getBillieReferenceId();
    }

}