<?php
namespace Application\Category;
use Zend\Form\Form as ZendForm;
use Zend\Form\Element;
use Zend\InputFilter\Factory as InputFilterFactory;
class Form extends ZendForm
{
    function __construct($product=null)
    {
        parent::__construct();

        $this->add([
            'name' => 'name',
            'options' => [
                'label' => 'Name',
            ],
            'type'  => 'Text',
        ]);

        $this->add([
            'name' => 'parents',
            'options' => [
                'label' => 'Parents',
            ],
            'type'  => 'Text',
        ]);

        $this->add([
            'name' => 'submit',
            'type'  => 'submit',
            'attributes'=>['value'=>'Save']
        ]);

        $inputFilterFactory = new InputFilterFactory();
        $inputFilter = $inputFilterFactory->createInputFilter([
            'name' => [
                'name'       => 'name',
                'required'   => true,
                'validators' => [
                    ['name' => 'not_empty'],
                    [
                        'name' => 'string_length',
                        'options' => ['min' => 3]
                    ],
                ],
            ]
        ]);
        $this->setInputFilter($inputFilter);

        $this->setHydrator(new \Zend\Stdlib\Hydrator\ClassMethods());

        if(isset($product)) {
            $this->bind($product);
        }

    }
}