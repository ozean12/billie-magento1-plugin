<?php


class Billie_Core_Helper_Log extends Mage_Core_Helper_Abstract
{


    public function billieLog($order, $request, $response)
    {

        $log = Mage::getModel('billie_core/transaction_log');

        $logData = array(

            'store_id' => Mage::app()->getStore()->getStoreId(),
            'order_id' => $order->getId(),
            'reference_id' => $response->referenceId ? $response->referenceId : $order->getBillieReferenceId(),
            'transaction_tstamp' => time(),
            'created_at' => $order->getCreatedAt(),
            'customer_id' => $order->getCustomerId(),
            'billie_state' => $response->state,
            'mode' => Mage::Helper('billie_core/sdk')->getMode() ? 'sandbox' : 'live',
            'request' => serialize($request),
            'billie_state' => $response->state
        );
        $log->setData($logData);
        $log->save();

    }


}