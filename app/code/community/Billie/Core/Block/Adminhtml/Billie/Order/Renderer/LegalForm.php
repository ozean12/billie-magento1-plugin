<?php
class Billie_Core_Block_Adminhtml_Billie_Order_Renderer_LegalForm extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $value =  $row->getData($this->getColumn()->getIndex());
        $payment = Mage::getModel('sales/order_payment')->load($value,'parent_id');
//        $payment = $order->getPaymentMethod();

        return Mage::helper('billie_core')->getLegalFormByCode($payment->getBillieLegalForm());
    }

}