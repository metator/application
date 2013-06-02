<?php
namespace Application\Product;
use Zend\Form\Form as ZendForm;
use Zend\Form\Element;
use Zend\InputFilter\Factory as InputFilterFactory;
class Form extends ZendForm
{
    function __construct()
    {
        parent::__construct();
        $this->add([
            'name' => 'sku',
            'options' => [
                'label' => 'SKU',
            ],
            'type'  => 'Text',
        ]);


        $inputFilterFactory = new InputFilterFactory();
        $inputFilter = $inputFilterFactory->createInputFilter([
            'sku' => [
                'name'       => 'sku',
                'required'   => true,
                'validators' => [
                    ['name' => 'not_empty'],
                    [
                        'name' => 'string_length',
                        'options' => ['min' => 3,'max' => 5]
                    ],
                ],
            ],
        ]);
        $this->setInputFilter($inputFilter);

        $this->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods());
        /*$this->addElement('text','name', array(
            'label'=>'Name',
            'required'=>true
        ));*/


        /*$this->addElement('radio','attribute_color',array(
            'label'=>'Configurable "color"',
            'multiOptions'=>array('yes'=>'Yes','no'=>'No'),
            'separator' => '',
            'value'=>'no',
            'class'=>"toggle_attribute {attributeName:'Color'}"
        ));
        $this->addElementForAttribute('Color');*/


    }

    /*function addElementForAttribute($attribute)
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
    }*/
}