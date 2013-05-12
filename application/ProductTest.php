<?php
/**
* Metator (http://metator.com/)
* @copyright  Copyright (c) 2013 Vehicle Fits, llc
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
class ProductTest extends PHPUnit_Framework_TestCase
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

    /**
     * @expectedException Exception
     */
    function testShouldNotGetInvalidAttribute()
    {
        $product = new Product;
        $product->attribute('invalid');
    }

    function testShouldAddAttributeByString()
    {
        $product = new Product;
        $product->addAttribute('color');
        $this->assertTrue($product->hasAttribute('color'),'should add attribute by string');
    }

    function testShouldHaveValueForAttribute()
    {
        $product = new Product;
        $product->addAttribute('color');
        $product->setAttributeValue('color','red');
        $this->assertEquals('red',$product->attributeValue('color'),'should set attribute value');
    }

    function testShouldHaveDifferentValuesForSameAttributeTwoProducts()
    {
        $product1 = new Product;
        $product2 = new Product;
        $attribute = new Attribute(array(
            'name'=>'color'
        ));
        $product1->addAttribute($attribute);
        $product2->addAttribute($attribute);
        $product1->setAttributeValue('color','red');
        $product2->setAttributeValue('color','blue');
        $this->assertEquals('red',$product1->attributeValue('color'),'should have different values for same attribute different products');
        $this->assertEquals('blue',$product2->attributeValue('color'),'should have different values for same attribute different products');
    }

    function testShouldAddAttributeOptionsByArray()
    {
        $product = new Product;
        $product->addAttribute('color', array(
            'options'=>array('red','blue')
        ));
        $this->assertEquals(array('red','blue'),$product->attribute('color')->options(),'should add attribute options by array');
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
            'flat_fee'=>5
        ));
        $product->addAttribute($attribute);
        $product->setAttributeValue('Color','red');
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
            'flat_fee'=>5
        ));

        // add "size" attribute
        $size = new Attribute(array(
            'name'=>'Size'
        ));
        $size->addOption('large', array(
            'flat_fee'=>5
        ));
        $product->addAttribute($color);
        $product->addAttribute($size);
        $product->setAttributeValue('Color','red');
        $product->setAttributeValue('Size','large');
        $this->assertEquals(15, $product->price(), 'should modify price with multiple attributes');
    }

    function testShouldAddPriceModifiersForOptionsByArray()
    {
        $product = new Product;
        $product->addAttribute('color', array(
            'options'=>array(
                'red'=>array('flat_fee'=>5),
                'blue'=>array('percentage'=>10)
            )
        ));
        $this->assertEquals(5,$product->attribute('color')->priceModifierForOption('red')->flatFee(),'should add flat fee price modifier by array');
        $this->assertEquals(10,$product->attribute('color')->priceModifierForOption('blue')->percentage(),'should add percentage price modifier by array');
    }
}