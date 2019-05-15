<?php

class Billie_Core_Model_Sales_Observer
{

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

            $client = Mage::Helper('billie_core/sdk')->clientCreate();

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

                $client = Mage::Helper('billie_core/sdk')->clientCreate();
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
            $client = Mage::Helper('billie_core/sdk')->clientCreate();

            $command =  Mage::Helper('billie_core/sdk')->cancel($order);
            $billieResponse = $client->cancelOrder($command);

        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }
    }

    public function updateOrder($observer)
    {

        $creditMemo = $observer->getEvent()->getCreditmemo();
        $order = $creditMemo->getOrder();

        $client = Mage::Helper('billie_core/sdk')->clientCreate();
        try {
            if ($order->canCreditmemo()) {

                $command = Mage::Helper('billie_core/sdk')->reduceAmount($order);
                $billieResponse = $client->reduceOrderAmount($command);

                if($billieResponse->state == 'complete'){

                    Mage::getSingleton('core/session')->addNotice(Mage::Helper('billie_core')->__('This transaction is already closed, refunds with billie payment are not possible anymore'));

                }

            } else {

                $command =  Mage::Helper('billie_core/sdk')->cancel($order);
                $billieResponse = $client->cancelOrder($command);

            }
        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }


    }

}