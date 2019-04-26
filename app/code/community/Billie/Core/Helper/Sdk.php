<?php

class Billie_Core_Helper_Sdk extends Mage_Core_Helper_Abstract
{

    /**
     * create billie CreateOrder object
     *
     * @param $order
     * @return object
     */
    public function mapData($order)
    {

        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $payment = $order->getPayment();

        try {
            $command = new Billie\Command\CreateOrder();

//// Company information
            $command->debtorCompany = new Billie\Model\Company('BILLIE-00000001', $billingAddress->getCompany(), $this->mapAddress($billingAddress));
            $command->debtorCompany->industrySector = $payment->getBillieIndustrySector();
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
            $command->amount = new Billie\Model\Amount($order->getGrandTotal(), $order->getGlobalCurrencyCode(), 19); // amounts are in cent!
//
//// Define the due date in DAYS AFTER SHIPPMENT
//        $command->duration = 14; // meaning: when the order is shipped on the 1st May, the


        } catch (Exception $e) {

            mage::log($e->getMessage(), null, 'hdtest.log', true);

        }

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
        $addressObj->houseNumber = '4';
        $addressObj->postalCode = $address->getPostcode();
        $addressObj->city = $address->getCity();
        $addressObj->countryCode = $address->getCountryId();

        return $addressObj;
    }

}