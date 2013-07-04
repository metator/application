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
    function testShouldRequireSku()
    {
        $form = new Form();
        $this->assertFalse($form->getInputFilter()->get('sku')->isValid());
    }

    function testShouldBeValidWhenHasSku()
    {
        $form = new Form();
        $form->setData(['sku'=>'foobar']);
        $form->setValidationGroup('sku');
        $this->assertTrue($form->isValid());
    }

    function testShouldRequireName()
    {
        $form = new Form();
        $this->assertFalse($form->getInputFilter()->get('name')->isValid());
    }

    function testShouldBeValidWhenHasName()
    {
        $form = new Form();
        $form->setData(['name'=>'widget']);
        $form->setValidationGroup('name');
        $this->assertTrue($form->isValid());
    }

    function testShouldGetValuesFromProduct()
    {
        $product = new Product([
            'sku'=>'foo'
        ]);
        $form = new Form($product);
        $this->assertEquals('foo', $form->get('sku')->getValue(), 'should copy sku from product to form');
    }

    function testShouldSetValuesToProduct()
    {
        $product = new Product;
        $form = new Form($product);
        $form->setData([
            'sku'=>'foo',
            'name'=>'widget',
            'basePrice'=>12.34
        ]);
        $this->assertTrue($form->isValid());
        $this->assertEquals('foo',$product->getSku(),'should copy sku from form to product');
        $this->assertEquals('widget',$product->getName(),'should copy name from form to product');
        $this->assertEquals(12.34,$product->getBasePrice(),'should copy base price from form to product');
    }
}