<?php

class Billie_Core_Adminhtml_Billie_OrderController extends Mage_Adminhtml_Controller_Action {

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('billie_core/billie_order');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('billie_core/adminhtml_billie_order_grid')->toHtml()
        );
    }
    public function viewAction()
    {
        $transactionEntry = $this->_initTransactionLog();

        $this->_title(sprintf('Transaction Log Reference ID #%s',$transactionEntry->getReferenceId()));


        $this->loadLayout();
        $this->renderLayout();
    }


    /**
     * @return $this
     *
     */

    protected function _initLayout()
    {
        $this->loadLayout()
            ->_setActiveMenu('billie_core');
        return $this;
    }

}