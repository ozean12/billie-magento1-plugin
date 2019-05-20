<?php

class Billie_Core_Adminhtml_Transaction_LogController extends Mage_Adminhtml_Controller_Action {

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('billie_core/transaction_log');
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
            $this->getLayout()->createBlock('billie_core/adminhtml_transaction_log_grid')->toHtml()
        );
    }
    public function viewAction()
    {
        $transactionEntry = $this->_initTransactionLog();

        $this->_title(sprintf('Transaction Log Reference ID #%s',$transactionEntry->getReferenceId()));


        $this->loadLayout();
        $this->renderLayout();
//        $this->_initLayout()
//            ->_addContent($this->getLayout()->createBlock('abo/adminhtml_abo_edit'))
//            ->renderLayout();
    }

    protected function _initTransactionLog() {

        $transactionEntityId = $this->getRequest()->getParam('id');

        if (isset($transactionEntityId)) {

            $transactionEntry = Mage::getModel('billie_core/transaction_log')->load($transactionEntityId);
            Mage::register('current_transactionEntry', $transactionEntry);
            return $transactionEntry;
        }
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