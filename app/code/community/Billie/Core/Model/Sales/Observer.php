<?php

class Billie_Core_Model_Sales_Observer
{

    const paymentmethodCode = 'payafterdelivery';

    public function createOrder($observer)
    {

        /** @var $paymentMethod Billie_Core_Model_Payment_Payafterdelivery */
        $order = $observer->getEvent()->getOrder();
        $payment = $order->getPayment();
        $paymentMethod = $payment->getMethodInstance();

        if ($paymentMethod->getCode() != self::paymentmethodCode) {
            return;
        }

        try {
            $billieOrderData = Mage::helper('billie_core/sdk')->mapCreateOrderData($order);
            // initialize Billie Client

            $client = Mage::Helper('billie_core/sdk')->clientCreate();

            $billieResponse = $client->createOrder($billieOrderData);
            $order->setData('billie_reference_id', $billieResponse->referenceId);
            $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery: payment accepted for %s', $billieResponse->referenceId));

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

        if ($payment->getCode() != self::paymentmethodCode) {
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
                $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery: shipping information was send for %s. The customer will be charged now', $billieResponse->referenceId));
                $order->save();

            } catch (Exception $error) {

                Mage::throwException($error->getMessage());

            }
        }

    }


    public function cancelOrder($observer)
    {
        $order = $observer->getEvent()->getOrder();
        $payment = $order->getPayment()->getMethodInstance();

        if ($payment->getCode() != self::paymentmethodCode) {
            return;
        }

        try {
            $client = Mage::Helper('billie_core/sdk')->clientCreate();

            $command = Mage::Helper('billie_core/sdk')->cancel($order);
            $billieResponse = $client->cancelOrder($command);
            $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery:  The transaction with the id %s was successfully canceled.', $order->getBillieReferenceId()));
            $order->save();


        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }
    }

    public function updateOrder($observer)
    {
        $creditMemo = $observer->getEvent()->getCreditmemo();
        $order = $creditMemo->getOrder();
        $payment = $order->getPayment()->getMethodInstance();

        if ($payment->getCode() != self::paymentmethodCode) {
            return;
        }

        $client = Mage::Helper('billie_core/sdk')->clientCreate();
        try {
            if ($order->canCreditmemo()) {

                $command = Mage::Helper('billie_core/sdk')->reduceAmount($order);
                $billieResponse = $client->reduceOrderAmount($command);

                if ($billieResponse->state == 'complete') {

                    Mage::getSingleton('core/session')->addNotice(Mage::Helper('billie_core')->__('This transaction is already closed, refunds with billie payment are not possible anymore'));

                } else {


                    $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery:  The amount for transaction with the id %s was successfully reduced.', $order->getBillieReferenceId()));
                    $order->save();

                }

            } else {

                $command = Mage::Helper('billie_core/sdk')->cancel($order);
                $billieResponse = $client->cancelOrder($command);

                $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery:  The transaction with the id %s was successfully canceled.', $order->getBillieReferenceId()));
                $order->save();

            }
        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }


    }

}