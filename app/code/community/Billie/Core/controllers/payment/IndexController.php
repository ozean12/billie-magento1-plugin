<?php

class Billie_Core_Payment_IndexController extends Mage_Core_Controller_Front_Action {

    public function redirectAction()
    {
        $order = new Mage_Sales_Model_Order();
        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order->loadByIncrementId($orderId);

        if ($this->getRequest()->get("flag") == "1" && $this->getRequest()->get("orderId"))
        {
            $orderId = $this->getRequest()->get("orderId");
            $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
            $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
            $order->save();

            Mage::getSingleton('checkout/session')->unsQuoteId();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=> false));
        }
        else
        {
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/error', array('_secure'=> false));
        }
    }

}