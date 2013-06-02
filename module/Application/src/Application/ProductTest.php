<?php
/**
* Metator (http://metator.com/)
* @copyright  Copyright (c) 2013 Vehicle Fits, llc
* @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/
use \Application\Product;
use \Application\Product\Configurable;
use \Application\Attribute;

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

    /**
     * @expectedException Exception
     */
    function testShouldDisallowAttributeValueNotInOptions()
    {
        $product = new Product;
        $product->addAttribute('color');
        $product->setAttributeValue('color','red');
    }

    function testShouldHaveValueForAttribute()
    {
        $product = new Product;
        $product->addAttribute('color', array(
            'options'=>array('red','blue')
        ));
        $product->setAttributeValue('color','red');
        $this->assertEquals('red',$product->attributeValue('color'),'should set attribute value');
    }

    function testShouldHaveDifferentValuesForSameAttributeTwoProducts()
    {
        $product1 = new Product;
        $product2 = new Product;
        $attribute = new Attribute(array(
            'name'=>'color',
            'options'=>array('red','blue')
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

    function testShouldNotHavePriceModifier()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array('red')
        ));
        $this->assertFalse($product->hasPriceModifier('color','red'), 'should not have price modifier');
    }

    function testShouldNotHavePriceModifierOption()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array('red')
        ));
        $this->assertFalse($product->hasPriceModifier('color','red'), 'should not have price modifier');
    }

    function testShouldGetFlatFeeWhenUsingAttributeFactory()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array(
                'red'=>array('flat_fee'=>5)
            )
        ));
        $this->assertEquals(5, $product->priceModifierFor('color','red')->flatFee(), 'should get flat fee when using attribute factory');
    }

    function testShouldGetPercentage()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array(
                'red'=>array('percentage'=>5)
            )
        ));
        $this->assertEquals(5, $product->priceModifierFor('color','red')->percentage(), 'should get percentage');
    }

    function testShouldGetFlatFeeWhenUsingAttributeObject()
    {
        $product = new Product(array(
            'price'=>5
        ));
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $attribute->addOption('red');
        $product->addAttribute($attribute,array(
            'options'=>array(
                'red'=>array('flat_fee'=>5)
            )
        ));
        $product->setAttributeValue('Color','red');
        $this->assertEquals(5, $product->priceModifierFor('Color','red')->flatFee(), 'should get flat fee when using attribute object');
    }

    function testShouldAddMarkupToExistingAttribute()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array('red')
        ));
        $product->addPriceModifiersForOption('color','red',array(
           'percentage'=>5
        ));
        $this->assertEquals(5, $product->priceModifierFor('color','red')->percentage(), 'should add markup to existing attribute');
    }

    function testShouldNotModifyPrice()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array('red')
        ));
        $price = $product->modifyPrice('color','red',5);
        $this->assertEquals(5, $price, 'should not modify price');
    }

    function testShouldModifyPriceFlatFee()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array(
                'red'=>array('flat_fee'=>5)
            )
        ));
        $price = $product->modifyPrice('color','red',5);
        $this->assertEquals(10, $price, 'should modify price by flat fee');
    }

    function testShouldModifyPricePercentage()
    {
        $product = new Product;
        $product->addAttribute('color',array(
            'options'=>array(
                'red'=>array('percentage'=>10)
            )
        ));
        $price = $product->modifyPrice('color','red',10);
        $this->assertEquals(11, $price, 'should modify price by percentage');
    }

    /**
     * @expectedException Exception
     */
    function testShouldDisallowInvalidValue()
    {
        $product = new Product;
        $product->modifyPrice('invalidAttrib','invalidValue',null);
    }

    function testShouldModifyPrice()
    {
        $product = new Product(array(
            'price'=>5
        ));
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $attribute->addOption('red');
        $product->addAttribute($attribute, array(
            'options'=>array(
                'red'=>array('flat_fee'=>5),
            )
        ));
        $product->setAttributeValue('Color','red');
        $this->assertEquals(10, $product->price(), 'should modify price');
    }

    function testShouldAddPriceModifiers()
    {
        $product = new Product;
        $product->addAttribute('color', array(
            'options'=>array(
                'red'=>array('flat_fee'=>5),
                'blue'=>array('percentage'=>10)
            )
        ));
        $this->assertEquals(5,$product->priceModifierFor('color','red')->flatFee(),'should add flat fee price modifier');
        $this->assertEquals(10,$product->priceModifierFor('color','blue')->percentage(),'should add percentage price modifier');
    }

    function testPriceModifiersShouldBePerProduct()
    {
        $product1 = new Product;
        $product2 = new Product;
        $product1->addAttribute('color', array(
            'options'=>array(
                'red'=>array('flat_fee'=>5),
            )
        ));
        $product2->addAttribute('color', array(
            'options'=>array(
                'red'=>array('flat_fee'=>10),
            )
        ));
        $this->assertEquals(5,$product1->priceModifierFor('color','red')->flatFee(),'price modifiers should be per product');
        $this->assertEquals(10,$product2->priceModifierFor('color','red')->flatFee(),'price modifiers should be per product');
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
        $color->addOption('red');

        // add "size" attribute
        $size = new Attribute(array(
            'name'=>'Size'
        ));
        $size->addOption('large');

        // add "size" & "color" attributes to products, and set price modifiers for their values
        $product->addAttribute($color, array(
            'options'=>array(
                'red'=>array('flat_fee'=>5),
            )
        ));
        $product->addAttribute($size, array(
            'options'=>array(
                'large'=>array('flat_fee'=>5),
            )
        ));
        $product->setAttributeValue('Color','red');
        $product->setAttributeValue('Size','large');
        $this->assertEquals(15, $product->price(), 'should modify price with multiple attributes');
    }

    function testShouldDerivePossibleOptionsForConfigurableProduct()
    {
        $product1 = new Product;
        $product2 = new Product;
        $attribute = new Attribute(array(
            'name'=>'color',
            'options'=>array('red','blue','green')
        ));
        $product1->addAttribute($attribute);
        $product2->addAttribute($attribute);
        $product1->setAttributeValue('color','red');
        $product2->setAttributeValue('color','blue');
        $configurableProduct= new Configurable;
        $configurableProduct->addProduct($product1);
        $configurableProduct->addProduct($product2);
        $actual = $configurableProduct->attribute('color')->options();
        $this->assertEquals(array('red','blue'), $actual, 'should derive configurable products options based on values for attribute in encapsulated products');
    }

}