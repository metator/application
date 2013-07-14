<?php
/**
* Metator (http://metator.com/)
* @copyright  Copyright (c) 2013 Vehicle Fits, llc
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
namespace Metator\Product;

use \Application\Attribute;

class ProductTest extends \PHPUnit_Framework_TestCase
{
    function testShouldSetIDThroughConstructor()
    {
        $product = new Product(array('id'=>1));
        $this->assertEquals(1,$product->id(),'should set ID through constructor');
    }

    function testShouldSetIDThroughSetter()
    {
        $product = new Product();
        $product->setId(1);
        $this->assertEquals(1,$product->id(),'should set ID through setter');
    }

    /**
     * @expectedException Exception
     */
    function testShouldDisallowChangingID()
    {
        $product = new Product();
        $product->setId(1);
        $product->setId(2);
    }

    function testShouldSetSkuThroughConstructor()
    {
        $product = new Product(array('sku'=>'sku123'));
        $this->assertEquals('sku123',$product->getSku(), 'should set sku through constructor');
    }

    function testShouldSetSkuThroughSetter()
    {
        $product = new Product;
        $product->setSku('sku123');
        $this->assertEquals('sku123',$product->getSku(), 'should set sku through setter');
    }
    
    function testShouldSetNameThroughConstructor()
    {
        $product = new Product(array('name'=>'widget'));
        $this->assertEquals('widget',$product->getName(), 'should set name through constructor');
    }

    function testShouldSetNameThroughSetter()
    {
        $product = new Product;
        $product->setName('widget');
        $this->assertEquals('widget',$product->getName(), 'should set name through setter');
    }

    function testShouldSetCategoriesThroughConstructor()
    {
        $product = new Product(array('categories'=>[1,2,3]));
        $this->assertEquals([1,2,3],$product->getCategories(), 'should set categories through constructor');
    }

    function testShouldSetCategoriesThroughSetter()
    {
        $product = new Product;
        $product->setCategories([1,2,3]);
        $this->assertEquals([1,2,3],$product->getCategories(), 'should set categories through setter');
    }

    function testShouldSetBasePriceThroughConstructor()
    {
        $product = new Product(array('base_price'=>5.15));
        $this->assertEquals(5.15,$product->getBasePrice(), 'should set base price through constructor');
    }

    function testShouldSetBasePriceThroughConstructorCamelCase()
    {
        $product = new Product(array('basePrice'=>5.15));
        $this->assertEquals(5.15,$product->getBasePrice(), 'should set base price through constructor w/ camel case');
    }

    function testShouldSetBasePriceThroughSetter()
    {
        $product = new Product;
        $product->setBasePrice(5.15);
        $this->assertEquals(5.15,$product->getBasePrice(), 'should set base price through setter');
    }

    function testShouldDefaultToNullValueForAttributes()
    {
        $product = new Product;
        $this->assertNull($product->attributeValue('color'),'should return NULL for an unset attribute');
    }

    function testShouldSetAttribute()
    {
        $product = new Product;
        $product->setAttributeValue('color','red');
        $this->assertEquals('red',$product->attributeValue('color'),'should set attribute value');
    }

    function testShouldGetAttributes()
    {
        $product = new Product;
        $product->setAttributeValue('color','red');
        $product->setAttributeValue('size','small');
        $expected = array(
            'color'=>'red',
            'size'=>'small'
        );
        $this->assertEquals($expected, $product->attributes(), 'should get attributes');
    }

    function testShouldSetAttributesThroughConstructor()
    {
        $product = new Product(array(
            'attributes'=>array(
                'color'=>'red',
                'size'=>'small'
            )
        ));
        $expected = array(
            'color'=>'red',
            'size'=>'small'
        );
        $this->assertEquals($expected, $product->attributes(), 'should set attributes through constructor');
    }

}