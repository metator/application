<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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