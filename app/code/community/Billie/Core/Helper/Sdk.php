<?php

use Billie\Command\CancelOrder;
use Billie\Command\ReduceOrderAmount;
use Billie\Model\Amount;

class Billie_Core_Helper_Sdk extends Mage_Core_Helper_Abstract
{

    const sandboxMode = 'payment/payafterdelivery/sandbox';
    const apiKey = 'payment/payafterdelivery/api_key';
    const housenumberField = 'billie_core/config/housenumber';
    const invoiceUrl = 'billie_core/config/invoice_url';


    /**
     * create billie CreateOrder object
     *
     * @param $order
     * @return object
     */
    public function mapCreateOrderData($order)
    {

        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $payment = $order->getPayment();
        $customerId = $order->getCustomerId()?$order->getCustomerId():'';


        $command = new Billie\Command\CreateOrder();

//// Company information
        $command->debtorCompany = new Billie\Model\Company($customerId, $billingAddress->getCompany(), $this->mapAddress($billingAddress));
        $command->debtorCompany->legalForm = $payment->getBillieLegalForm();
//
//// Information about the person
        $command->debtorPerson = new Billie\Model\Person($billingAddress->getEmail());
        $command->debtorPerson->salution = ($order->getCustomerGender() ? 'm' : 'f');
//
//// Delivery Address
        $command->deliveryAddress = $this->mapAddress($shippingAddress);
//
//// Amount
///  TODO if Grandtotal includes tax
        $command->amount = new Billie\Model\Amount(($order->getBaseSubtotal()+$order->getBaseShippingAmount())*100, $order->getGlobalCurrencyCode(), $order->getBaseTaxAmount()*100); // amounts are in cent!
//
//// Define the due date in DAYS AFTER SHIPPMENT
//        $command->duration = 14; // meaning: when the order is shipped on the 1st May, the

        return $command;

    }

    /**
     * create billie ShipOrder object
     *
     * @param $order
     * @return object
     */
    public function mapShipOrderData($order)
    {

        $command = new Billie\Command\ShipOrder($order->getBillieReferenceId());
        $command->orderId = $order->getIncrementId();
        $command->invoiceNumber = $order->getInvoiceCollection()->getFirstItem()->getIncrementId();
        $command->invoiceUrl = str_replace('//','/',Mage::getStoreConfig(self::invoiceUrl).DS.$order->getIncrementId().'.pdf');

        return $command;
    }

    /**
     * maps magento object to billie object
     *
     * @param $address
     * @return object
     */

    public function mapAddress($address)
    {

        $addressObj = new Billie\Model\Address();
        $addressObj->street = $address->getStreet()[0];
        $addressObj->houseNumber = (Mage::getStoreConfig(self::housenumberField) != 'street')?$address->getData(Mage::getStoreConfig(self::housenumberField)):$address->getStreet()[1];
        $addressObj->postalCode = $address->getPostcode();
        $addressObj->city = $address->getCity();
        $addressObj->countryCode = $address->getCountryId();

        return $addressObj;
    }

    /**
     * @return \Billie\HttpClient\BillieClient
     */


    public function clientCreate(){

        return Billie\HttpClient\BillieClient::create(Mage::getStoreConfig(self::apiKey), Mage::getStoreConfig(self::sandboxMode));
    }

    /**
     * @param $order
     * @return ReduceOrderAmount
     *
     */

    public function reduceAmount($order){

        $command = new Billie\Command\ReduceOrderAmount($order->getBillieReferenceId());
        $newTotalAmount = $order->getData('subtotal_invoiced') - $order->getData('subtotal_refunded');
        $newTaxAmount = $order->getData('tax_invoiced') - $order->getData('tax_refunded');
        $command->amount = new Billie\Model\Amount($newTotalAmount*100, $order->getData('base_currency_code'), $newTaxAmount*100);

        return $command;

    }

    /**
     * @param $order
     * @return CancelOrder
     *
     */

    public function cancel($order){

        return new Billie\Command\CancelOrder($order->getBillieReferenceId());


    }


}