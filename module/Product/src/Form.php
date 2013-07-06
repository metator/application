<?php
namespace Metator\Product;

class Form extends \Zend_Form
{
    function __construct($categoryMapper, $product=null)
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

        $this->addElement('multiCheckbox','categories',array(
            'label'=>'Categories',
            'multiOptions'=>$categoryMapper ? $categoryMapper->listForForm() : array(),
            'separator'=>''
        ));

        $this->addElement('file','image_to_add',array(
            'label'=>'Image To Add'
        ));

    }
}