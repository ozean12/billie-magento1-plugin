<?php
class Billie_Core_Block_Payment_Info_Payafterdelivery extends Mage_Payment_Block_Info
{

    const duration = 'payment/payafterdelivery/duration';
    const billieBank = 'Deutsche Handelsbank';


    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation)
        {
            return $this->_paymentSpecificInformation;
        }

        $data = array();
        if ($this->getInfo()->getBillieLegalForm() && !$this->isAdmin()) {
            $data[Mage::helper('billie_core')->__('Legal Form')] = $this->helper('billie_core')->getLegalFormByCode($this->getInfo()->getBillieLegalForm());
        }
        if ($this->isAdmin()) {
            $data[Mage::helper('billie_core')->__('Account owner')] = $this->getStoreName();
        }
        if ($this->getInfo()->getBillieViban()) {
            $data[Mage::helper('billie_core')->__('VIBAN')] = $this->getInfo()->getBillieViban();
        }
        if ($this->getInfo()->getBillieVbic()) {
            $data[Mage::helper('billie_core')->__('VBIC')] = $this->getInfo()->getBillieVbic();
        }
        if ($this->getInfo()->getBillieViban()) {
            $data[Mage::helper('billie_core')->__('Bank')] = SELF::billieBank;
        }
        if ($this->getInfo()->getBillieDuration()) {
            $data[Mage::helper('billie_core')->__('Duration')] = $this->getDuration();
        }
        if($this->getInfo()->getOrder()){
            $invoiceIncrementId = $this->getInvoiceIncrementId($this->getInfo()->getOrder());
            if ($this->getInfo()->getBillieViban() && $invoiceIncrementId) {
                $data[Mage::helper('billie_core')->__('Usage')] = $invoiceIncrementId;
            }
        }
        if ($this->getInfo()->getBillieRegistrationNumber() && !$this->isAdmin()) {
            $data[Mage::helper('billie_core')->__('Registration Number')] = $this->getInfo()->getBillieRegistrationNumber();
        }
        if ($this->getInfo()->getBillieTaxId() && !$this->isAdmin()) {
            $data[Mage::helper('billie_core')->__('VAT ID')] = $this->getInfo()->getBillieTaxId();
        }


        $transport = parent::_prepareSpecificInformation($transport);

        return $transport->addData(array_merge($data, $transport->getData()));
    }

    public function isAdmin()
    {
        if(Mage::app()->getStore()->isAdmin()){
            return true;
        }

        if(Mage::getDesign()->getArea() == 'adminhtml'){
            return true;
        }
        return false;
    }

    protected function getStoreName()
    {

        return Mage::getStoreConfig('general/imprint/company_first', $this->getStoreId());
    }

    protected function getStoreId()
    {

        $info = $this->getInfo();

        return $info->getOrder()->getStoreId();
    }

    protected function getDuration()
    {

        $info = $this->getInfo();
        $order = $info->getOrder();
        $shipping = $order->getShipmentsCollection()->getFirstItem();

        if ($shipping->getCreatedAt()) {

            $date = strtotime($shipping->getCreatedAt());
            $newDate = date('d.m.Y', strtotime(Mage::getStoreConfig(self::duration) . " day", $date));
            $duration = $newDate;

        } else {

            $duration = Mage::helper('billie_core')->__('Order is not shipped yet');

        }

        return $duration;

    }

    protected function getInvoiceIncrementId($order){

        $invoiceIncrementId = '';

        $invoiceCollection = $order->getInvoiceCollection();
        if(count($invoiceCollection) > 0){
            $invoice = $order->getInvoiceCollection()->getFirstItem();
            $invoiceIncrementId = $invoice->getIncrementId();
        }

        return $invoiceIncrementId;

    }

}
