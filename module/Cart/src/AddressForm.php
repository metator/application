<?php
namespace Metator\Cart;

class AddressForm extends \Zend_Form
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
        $this->addElement('text','address1',array(
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
        $this->addElement('text','zip',array(
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