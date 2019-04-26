<?php

class Billie_Core_Model_Sales_Observer
{
    private $apiKey = 'test-ralph';

    public function createOrder($observer)
    {

        $order = $observer->getEvent()->getOrder();

        try {
            $billieOrderData = Mage::helper('billie_core/sdk')->mapData($order);
// initialize Billie Client
            $client = Billie\HttpClient\BillieClient::create($this->apiKey, true); // SANDBOX MODE

            $billieResponse = $client->createOrder($billieOrderData);
            $order->setData('billie_reference_id', $billieResponse->referenceId);
            $order->save();

        } catch (Exception $e) {
            Mage::throwException($e->getMessage());

        }

    }


}