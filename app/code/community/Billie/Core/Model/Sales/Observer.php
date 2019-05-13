<?php

class Billie_Core_Model_Sales_Observer
{
    const apiKey = 'payment/payafterdelivery/api_key';
    const sandboxMode = 'payment/payafterdelivery/sandbox';


    public function createOrder($observer)
    {

        /** @var $paymentMethod Billie_Core_Model_Payment_Payafterdelivery */
        $order = $observer->getEvent()->getOrder();
        $payment = $order->getPayment();
        $paymentMethod = $payment->getMethodInstance();

        if ($paymentMethod->getCode() != "payafterdelivery") {
            return;
        }

        try {
            $billieOrderData = Mage::helper('billie_core/sdk')->mapCreateOrderData($order);
            // initialize Billie Client

            $client = Billie\HttpClient\BillieClient::create(Mage::getStoreConfig(self::apiKey), Mage::getStoreConfig(self::sandboxMode)); // SANDBOX MODE

            $billieResponse = $client->createOrder($billieOrderData);

            $order->setData('billie_reference_id', $billieResponse->referenceId);
            $payment->setData('billie_viban', $billieResponse->bankAccount->iban);
            $payment->setData('billie_vbic', $billieResponse->bankAccount->bic);
            $payment->save();
            $order->save();

        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }

    }


    public function shipOrder($observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();
        $payment = $order->getPayment()->getMethodInstance();

        if ($payment->getCode() != "payafterdelivery") {
            return;
        }

        $invoiceIds = $order->getInvoiceCollection()->getAllIds();

        if (!$invoiceIds) {

            Mage::throwException('You have to create a invoice first');

        } else {

            try {

                $billieShipData = Mage::helper('billie_core/sdk')->mapShipOrderData($order);

                $client = Billie\HttpClient\BillieClient::create(Mage::getStoreConfig(self::apiKey), Mage::getStoreConfig(self::sandboxMode)); // SANDBOX MODE
                $billieResponse = $client->shipOrder($billieShipData);

            } catch (Exception $e) {

                Mage::throwException($e->getMessage());

            }
        }

    }


    public function cancelOrder($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $payment = $order->getPayment()->getMethodInstance();

        if ($payment->getCode() != "payafterdelivery") {
            return;
        }

        try {
            $client = Billie\HttpClient\BillieClient::create(Mage::getStoreConfig(self::apiKey), Mage::getStoreConfig(self::sandboxMode)); // SANDBOX MODE

            $command = new Billie\Command\CancelOrder($order->getBillieReferenceId());
            $billieResponse = $client->cancelOrder($command);

        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }
    }

}