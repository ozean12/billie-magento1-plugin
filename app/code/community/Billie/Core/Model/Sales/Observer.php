<?php

class Billie_Core_Model_Sales_Observer
{
    private $apiKey = 'test-ralph';

    public function createOrder($observer)
    {

        $order = $observer->getEvent()->getOrder();

        try {
            $billieOrderData = Mage::helper('billie_core/sdk')->mapCreateOrderData($order);
// initialize Billie Client
            $client = Billie\HttpClient\BillieClient::create($this->apiKey, true); // SANDBOX MODE

            $billieResponse = $client->createOrder($billieOrderData);
            $order->setData('billie_reference_id', $billieResponse->referenceId);
            $order->save();

        } catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

    }

    public function shipOrder($observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != "payafterdelivery") {
            return;
        }

        $invoiceIds = $order->getInvoiceCollection()->getAllIds();

        if (!$invoiceIds) {

            Mage::throwException('You have to create a invoice first');

        } else {

            try {

                $billieShipData = Mage::helper('billie_core/sdk')->mapShipOrderData($order);

                $client = Billie\HttpClient\BillieClient::create($this->apiKey, true); // SANDBOX MODE
                $billieResponse = $client->shipOrder($billieShipData);

            } catch (Exception $e) {

                Mage::Log($e->getMessage(), null, 'hdtest.log', true);
                Mage::throwException($e->getMessage());

            }
        }

    }


    public function cancelOrder($observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != "payafterdelivery") {
            return;
        }

        try {
            $client = Billie\HttpClient\BillieClient::create($this->apiKey, true); // SANDBOX MODE

            $command = new Billie\Command\CancelOrder($order->getBillieReferenceId());
            $billieResponse = $client->cancelOrder($command);

        } catch (Exception $e) {

            Mage::Log($e->getMessage(), null, 'hdtest.log', true);
            Mage::throwException($e->getMessage());

        }
    }


}