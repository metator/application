<?php
namespace Metator\Cart;

class CheckoutForm extends \Zend_Form
{
    function init()
    {
        $shipping = new AddressForm;
        $shipping->setSubFormDecorators(array(
            'FormElements',
            'Fieldset'
        ))
        ->setLegend('Shipping Address');

        $billing = new AddressForm;
        $billing->setSubFormDecorators(array(
            'FormElements',
            'Fieldset'
        ))
        ->setLegend('Billing Address');

        $this->addSubForm($shipping, 'shipping');
        $this->addSubForm($billing, 'billing');

        foreach($this->billing->getElements() as $element) {
            $element->setRequired(false);
        }
    }
}