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

        if ($this->getInfo()->getBillieIndustrySector())
        {
            $data[Mage::helper('payment')->__('Industry Sector')] = $this->getInfo()->getBillieIndustrySector();
        }

        $transport = parent::_prepareSpecificInformation($transport);

        return $transport->setData(array_merge($data, $transport->getData()));
    }
}
