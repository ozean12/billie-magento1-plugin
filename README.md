#Billie Pay after delivery

Billie Paymentmethod for Magento 1.9.x

## Requirements
- PHP 5.6 or higher 
- 1.9.0.0 or higher

## Installation

Download Source and copy into Magento root folder<br/>
Clear Magento Caches and login again<br/>
Goto System -> Configuration -> Billie and configure housenumber and invoice URL<br/>
Goto Paymentmethods and configure Billie PayAfterDelivery method.<br/>
Goto System -> Configuration -> Customer -> Create Account -> enable Vat visibility in frontend
Ready to go

##Information

by install billie pay after delivery. The company field becomes mandatory. 

Change in file app/design/frontend/base/default/template/checkout/onepage/billing.phtml and app/design/frontend/base/default/template/persistent/onepage/billing.phtml or in your local theme folder


    <label for="billing:company"><?php echo $this->__('Company') ?></label>
    <div class="input-box">
        <input type="text" id="billing:company" name="billing[company]" value="<?php echo $this->escapeHtml($this->getAddress()->getCompany()) ?>" title="<?php echo Mage::helper('core')->quoteEscape($this->__('Company')) ?>" class="input-text <?php echo $this->helper('customer/address')->getAttributeValidationClass('company') ?>" />
    </div>    
to
    
    <label for="billing:company" class="required"><em>*</em><?php echo $this->__('Company') ?></label>
    <div class="input-box">
        <input type="text" id="billing:company" name="billing[company]" value="<?php echo $this->escapeHtml($this->getAddress()->getCompany()) ?>" title="<?php echo Mage::helper('core')->quoteEscape($this->__('Company')) ?>" class="input-text required <?php echo $this->helper('customer/address')->getAttributeValidationClass('company') ?>" />                        
    </div>    

## Contact
Billie GmbH<br/>
Charlottenstraße 4<br/>
10969 Berlin<br/>

## License