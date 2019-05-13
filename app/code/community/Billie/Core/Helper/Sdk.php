<?php

class Billie_Core_Helper_Sdk extends Mage_Core_Helper_Abstract
{

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


}