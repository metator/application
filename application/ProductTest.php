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

    function testShouldHaveNoAttributes()
    {
        $product = new Product;
        $this->assertEquals(array(), $product->attributes(), 'should have no attributes');
    }

    function testShouldAddAttribute()
    {
        $product = new Product;
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $product->addAttribute($attribute);
        $this->assertSame($attribute, $product->attributes()[0], 'should add attribute');
    }

    function testShouldGetAttribute()
    {
        $product = new Product;
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $product->addAttribute($attribute);
        $this->assertSame($attribute, $product->attribute('Color'), 'should get attribute');
    }

    function testShouldModifyPrice()
    {
        $product = new Product(array(
            'price'=>5
        ));
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $attribute->addOption('red', array(
            'price_modifier'=>new PriceModifier(array(
                'flat_fee'=>5
            ))
        ));
        $product->addAttribute($attribute);
        $product->attribute('Color')->setValue('red');
        $this->assertEquals(10, $product->price(), 'should modify price');
    }
}