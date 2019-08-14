<?php


class Billie_Core_Model_Resource_Transaction_Log_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('billie_core/transaction_log');
    }


	
}
