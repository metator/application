<?php
namespace Application\Product;
use Zend\Form\Form as ZendForm;
use Zend\Form\Element;
use Zend\InputFilter\Factory as InputFilterFactory;
class Form extends ZendForm
{
    function __construct($product=null)
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

        if($product) {
            $this->bind($product);
        }

    }
}