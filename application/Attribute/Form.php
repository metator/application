<?php
class Attribute_Form extends Zend_Form
{
    function init()
    {
        $this->addElement('text','name', array(
            'label'=>'Name',
            'required'=>true
        ));
        $this->addElement('text','1', array(
            'label'=>'Values',
            'required'=>true,
            'belongsTo'=>'value'
        ));

        $this->addElement('button', 'addElement', array(
            'label' => 'Add'
        ));

        $this->addElement('button', 'removeElement', array(
            'label' => 'Remove'
        ));

        $this->addElement('submit','save', array(
            'label'=>'Save'
        ));

        $this->addElement('hidden', 'id', array(
            'value' => 2
        ));

    }
}