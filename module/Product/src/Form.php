<?php
namespace Product;

class Form extends \Zend_Form
{
    function __construct($product=null)
    {
        parent::__construct();

        $this->addElement('text','sku',array(
            'label'=>'SKU',
            'required'=>true
        ));

        $this->addElement('text','name',array(
            'label'=>'Name',
            'required'=>true
        ));

        $this->addElement('text','basePrice',array(
            'label' => 'Base Price',
        ));

        $this->addElement('submit','submit',[
            'label'=>'Save'
        ]);

    }
}