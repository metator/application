<?php
class ProductTest extends PHPUnit_Framework_TestCase
{
    function testShouldBeAbleToSetProductNameThroughConstructor()
    {
        $product = new Product(array('name'=>'widget'));
        $this->assertEquals('widget',$product->name(), 'should be able to set name through constructor');
    }
}