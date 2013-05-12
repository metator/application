<?php
class Product_Form extends Zend_Form
{
    function init()
    {
        $this->addElement('text','sku', array(
            'label'=>'SKU',
            'required'=>true
        ));
        $this->addElement('text','name', array(
            'label'=>'Name',
            'required'=>true
        ));
        $this->addElement('text','inventory', array(
            'label'=>'Inventory'
        ));
        $this->addElement('multiCheckbox','categories',array(
            'label'=>'Categories',
            'multiOptions'=>array('foo'=>'Foo','bar')
        ));
        $this->addElement('submit','save', array(
            'label'=>'Save'
        ));
    }
}