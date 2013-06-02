<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\Product\Form;
use \Application\Product;

class FormTest extends PHPUnit_Framework_TestCase
{
    function testShouldGetValuesFromModel()
    {
        $product = new Product(['sku'=>'foo']);
        $form = new Form($product);
        $this->assertEquals('foo', $form->get('sku')->getValue(), 'should copy sku from product to form');
    }
}