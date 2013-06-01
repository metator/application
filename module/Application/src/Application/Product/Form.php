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
            'multiOptions'=>array('Foo','bar'),
            'separator' => ''
        ));
        $this->addElement('radio','attribute_color',array(
            'label'=>'Configurable "color"',
            'multiOptions'=>array('yes'=>'Yes','no'=>'No'),
            'separator' => '',
            'value'=>'no',
            'class'=>"toggle_attribute {attributeName:'Color'}"
        ));
        $this->addElementForAttribute('Color');
        $this->addElement('submit','save', array(
            'label'=>'Save'
        ));
    }

    function addElementForAttribute($attribute)
    {
        $this->addConfigurationElementsForAttributeOption($attribute,'red');
        $this->addConfigurationElementsForAttributeOption($attribute,'blue');

    }

    function addConfigurationElementsForAttributeOption($attribute,$option)
    {
        $this->addElement('radio',"attribute_{$attribute}_{$option}_pricemodifier_type",array(
            'label'=>"{$attribute} {$option} Price",
            'multiOptions'=>array('none','flat_fee','percentage'),
            'separator' => '',
            'class'=>'configure_attribute_'.$attribute
        ));
        $this->addElement('text',"attribute_{$attribute}_{$option}_pricemodifier_amount",array(
            'label'=>"Amount",
            'separator' => '',
            'class'=>'configure_attribute_'.$attribute
        ));
    }
}