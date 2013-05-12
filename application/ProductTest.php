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

    function testShouldHaveAttributeName()
    {
        $product = new Product;
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $product->addAttribute($attribute);
        $this->assertTrue($product->hasAttribute('Color'), 'should have attribute name');
    }

    function testShouldHaveAttributeObject()
    {
        $product = new Product;
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $product->addAttribute($attribute);
        $this->assertTrue($product->hasAttribute($attribute), 'should have attribute object');
    }

    function testShouldNotHaveAttribute()
    {
        $product = new Product;
        $this->assertFalse($product->hasAttribute('Color'), 'should not have attribute');
    }

    /**
     * @expectedException Exception
     */
    function testShouldDisallowDuplicateAttributes()
    {
        $product = new Product;
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $product->addAttribute($attribute);
        $product->addAttribute($attribute);
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

    function testShouldModifyPriceWithMultipleAttributes()
    {
        $product = new Product(array(
            'price'=>5
        ));

        // add "color" attribute
        $color = new Attribute(array(
            'name'=>'Color'
        ));
        $color->addOption('red', array(
            'price_modifier'=>new PriceModifier(array(
                'flat_fee'=>5
            ))
        ));

        // add "size" attribute
        $size = new Attribute(array(
            'name'=>'Size'
        ));
        $size->addOption('large', array(
            'price_modifier'=>new PriceModifier(array(
                'flat_fee'=>5
            ))
        ));
        $product->addAttribute($color);
        $product->addAttribute($size);
        $product->attribute('Color')->setValue('red');
        $product->attribute('Size')->setValue('large');
        $this->assertEquals(15, $product->price(), 'should modify price with multiple attributes');
    }
}