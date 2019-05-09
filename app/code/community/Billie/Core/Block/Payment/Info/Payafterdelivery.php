<?php
class Billie_Core_Block_Payment_Info_Payafterdelivery extends Mage_Payment_Block_Info
{
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation)
        {
            return $this->_paymentSpecificInformation;
        }
        
        $data = array();
        if ($this->getInfo()->getBillieLegalForm())
        {
            $data[Mage::helper('payment')->__('Legal Form')] = $this->helper('billie_core')->getLegalFormByCode($this->getInfo()->getBillieLegalForm());
        }
        if ($this->getInfo()->getBillieViban())
        {
            $data[Mage::helper('payment')->__('VIBAN')] = $this->getInfo()->getBillieViban();
        }


        $transport = parent::_prepareSpecificInformation($transport);

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}
