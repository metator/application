<?php
class ProductTest extends PHPUnit_Framework_TestCase
{
    function testShouldSetNameThroughConstructor()
    {
        $product = new Product(array('name'=>'widget'));
        $this->assertEquals('widget',$product->name(), 'should set name through constructor');
    }

    function testShouldSetNameThroughSetter()
    {
        $product = new Product;
        $product->setName('widget');
        $this->assertEquals('widget',$product->name(), 'should set name through setter');
    }

    function testShouldSetPriceThroughConstructor()
    {
        $product = new Product(array('price'=>5.15));
        $this->assertEquals(5.15,$product->price(), 'should set price through constructor');
    }

    function testShouldSetPriceThroughSetter()
    {
        $product = new Product;
        $product->setPrice(5.15);
        $this->assertEquals(5.15,$product->price(), 'should set price through setter');
    }
}