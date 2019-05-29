<?php

use Billie\Exception\InvalidCommandException;

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

        $billieOrderData = Mage::helper('billie_core/sdk')->mapCreateOrderData($order);

//        mage::log($billieOrderData,null,'hdtedt.log',true);
        try {
            // initialize Billie Client

            $client = Mage::Helper('billie_core/sdk')->clientCreate();

            $billieResponse = $client->createOrder($billieOrderData);
            Mage::Helper('billie_core/log')->billieLog($order, $billieOrderData, $billieResponse);

            $order->setData('billie_reference_id', $billieResponse->referenceId);
            $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery: payment accepted for %s', $billieResponse->referenceId));

            $payment->setData('billie_viban', $billieResponse->bankAccount->iban);
            $payment->setData('billie_vbic', $billieResponse->bankAccount->bic);
            $payment->save();
            $order->save();



        }catch (InvalidCommandException $e){

            $errorMsg = $e->getViolations()[0];

            Mage::Helper('billie_core/log')->billieLog($order, $billieOrderData, $errorMsg);
            Mage::throwException($errorMsg);

        }
        catch (Exception $e) {

            mage::log($e->getViolations,null,'hdtedt.log',true);
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

                Mage::Helper('billie_core/log')->billieLog($order, $billieShipData, $billieResponse);
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

            $billieCancelData = Mage::Helper('billie_core/sdk')->cancel($order);
            $billieResponse = $client->cancelOrder($billieCancelData);

            Mage::Helper('billie_core/log')->billieLog($order, $billieCancelData, $billieResponse);
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

                $billieUpdateData = Mage::Helper('billie_core/sdk')->reduceAmount($order);
                $billieResponse = $client->reduceOrderAmount($billieUpdateData);

                Mage::Helper('billie_core/log')->billieLog($order, $billieUpdateData, $billieResponse);

                if ($billieResponse->state == 'complete') {

                    Mage::getSingleton('core/session')->addNotice(Mage::Helper('billie_core')->__('This transaction is already closed, refunds with billie payment are not possible anymore'));

                } else {


                    $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery:  The amount for transaction with the id %s was successfully reduced.', $order->getBillieReferenceId()));
                    $order->save();

                }

            } else {

                $billieCancelData = Mage::Helper('billie_core/sdk')->cancel($order);
                $billieResponse = $client->cancelOrder($billieCancelData);

                Mage::Helper('billie_core/log')->billieLog($order, $billieCancelData, $billieResponse);
                $order->addStatusHistoryComment(Mage::Helper('billie_core')->__('Billie PayAfterDelivery:  The transaction with the id %s was successfully canceled.', $order->getBillieReferenceId()));
                $order->save();

            }
        } catch (Exception $e) {

            Mage::throwException($e->getMessage());

        }


    }

}