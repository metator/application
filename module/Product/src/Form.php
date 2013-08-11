<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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

        $this->addElement('textarea','description',array(
            'label'=>'Description',
            'required'=>true,
            'attribs'=>array(
                'rows'=>'5',
                'cols'=>'50',
            )
        ));

        $this->addElement('text','basePrice',array(
            'label' => 'Base Price',
        ));

        $this->addElement('multiCheckbox','categories',array(
            'label'=>'Categories',
            'multiOptions'=>$categoryMapper ? $categoryMapper->listForProductForm() : array(),
            'separator'=>''
        ));

        $this->addElement('file','image_to_add',array(
            'label'=>'Image To Add'
        ));

    }
}