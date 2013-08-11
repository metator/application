<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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

        $this->addElement('multiCheckbox','paths',array(
            'label'=>'Parents',
            'multiOptions'=>$mapper ? $mapper->listForCategoryForm() : array(),
            'separator'=>''
        ));
    }
}