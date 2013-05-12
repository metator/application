<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AttributeTest extends PHPUnit_Framework_TestCase
{
    function testShouldSetIDThroughConstructor()
    {
        $attribute = new Attribute(array('id'=>1));
        $this->assertEquals(1,$attribute->id(),'should set ID through constructor');
    }

    function testShouldSetIDThroughSetter()
    {
        $attribute = new Attribute();
        $attribute->setId(1);
        $this->assertEquals(1,$attribute->id(),'should set ID through setter');
    }

    /**
     * @expectedException Exception
     */
    function testShouldDisallowChangingID()
    {
        $attribute = new Attribute();
        $attribute->setId(1);
        $attribute->setId(2);
    }

    function testShouldSetNameThroughConstructor()
    {
        $attribute = new Attribute(array('name'=>'color'));
        $this->assertEquals('color',$attribute->name(), 'should set name through constructor');
    }

    function testShouldSetNameThroughSetter()
    {
        $attribute = new Attribute;
        $attribute->setName('color');
        $this->assertEquals('color',$attribute->name(), 'should set name through setter');
    }

    function testShouldHaveNoOptions()
    {
        $attribute = new Attribute;
        $this->assertEquals(array(), $attribute->options(), 'should have no options');
    }

    function testShouldNotHaveOption()
    {
        $attribute = new Attribute;
        $this->assertFalse($attribute->hasOption('foo'), 'should not have option');
    }

    function testShouldAddOption()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $this->assertEquals(array('red'),$attribute->options(),'should add option');
    }

    function testShouldHaveOption()
    {
        $attribute = new Attribute;
        $attribute->addOption('foo');
        $this->assertTrue($attribute->hasOption('foo'), 'should have option');
    }

    function testShouldAddMultipleOptions()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $attribute->addOption('blue');
        $attribute->addOption('green');
        $this->assertEquals(array('red','blue','green'),$attribute->options(),'should add multiple options');
    }

    function testShouldNotHavePriceModifier()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $this->assertFalse($attribute->hasPriceModifier('red'), 'should not have price modifier');
    }

    function testShouldNotModifyPrice()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $price = $attribute->modifyPrice('red',5);
        $this->assertEquals(5, $price, 'should not modify price');
    }

    function testShouldNotHavePriceModifierOption()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $this->assertFalse($attribute->priceModifierForOption('red'), 'should not have flat fee for option');
    }

    function testShouldGetFlatFee()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'flat_fee'=>5
        ));
        $this->assertEquals(5, $attribute->priceModifierForOption('red')->flatFee(), 'should get flat fee');
    }

    function testShouldModifyPriceFlatFee()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'flat_fee'=>5
        ));
        $price = $attribute->modifyPrice('red',5);
        $this->assertEquals(10, $price, 'should modify price by flat fee');
    }

    function testShouldModifyPricePercentage()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'percentage'=>10
        ));
        $price = $attribute->modifyPrice('red',10);
        $this->assertEquals(11, $price, 'should modify price by percentage');
    }

    function testShouldGetPercentage()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'percentage'=>5
        ));
        $this->assertEquals(5, $attribute->priceModifierForOption('red')->percentage(), 'should get percentage');
    }

    /**
     * @expectedException Exception
     */
    function testShouldDisallowInvalidValue()
    {
        $attribute = new Attribute;
        $attribute->modifyPrice('invalid',null);
    }

}