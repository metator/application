<?php
namespace Metator\Category;

class Form extends \Zend_Form
{
    function __construct($mapper)
    {
        parent::__construct();

        $this->addElement('text','name',array(
            'label'=>'Name',
            'required'=>true
        ));

        $this->addElement('multiCheckbox','parents',array(
            'label'=>'Parents',
            'multiOptions'=>$mapper ? $mapper->listForForm() : array(),
            'separator'=>''
        ));
    }
}