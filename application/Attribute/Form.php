<?php
class Attribute_Form extends Zend_Form
{
    function init()
    {
        $this->addElement('text','name', array(
            'label'=>'Name',
            'required'=>true
        ));
        $this->addElement('textarea','values', array(
            'label'=>'Values',
            'required'=>true,
            'description'=>'One value per line'
        ));


        $this->addElement('submit','save', array(
            'label'=>'Save'
        ));

    }

}