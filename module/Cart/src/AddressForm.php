<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Cart;

class AddressForm extends \Zend_Form_SubForm
{
    function init()
    {
        $this->addElement('text','first_name',array(
            'label'=>'First Name',
            'required'=>true
        ));
        $this->addElement('text','last_name',array(
            'label'=>'Last Name',
            'required'=>true
        ));
        $this->addElement('text','email',array(
            'label'=>'Email',
            'required'=>true
        ));
        $this->addElement('text','address',array(
            'label'=>'Address',
            'required'=>true
        ));
        $this->addElement('text','address2',array(
            'label'=>'Address (Line 2)',
            'required'=>true
        ));
        $this->addElement('text','city',array(
            'label'=>'City',
            'required'=>true
        ));
        $this->addElement('text','state',array(
            'label'=>'State',
            'required'=>true
        ));
        $this->addElement('text','postal',array(
            'label'=>'Zip/Postal Code',
            'required'=>true
        ));
        $this->addElement('text','country',array(
            'label'=>'Country',
            'required'=>true
        ));
        $this->addElement('text','phone',array(
            'label'=>'Phone',
            'required'=>true
        ));
        $this->addElement('text','fax',array(
            'label'=>'Fax',
            'required'=>true
        ));

    }
}