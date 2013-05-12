<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AttributeTest extends PHPUnit_Framework_TestCase
{
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

    function testShouldAddOption()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $this->assertEquals(array('red'),$attribute->options(),'should add option');
    }

    function testShouldAddMultipleOptions()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $attribute->addOption('blue');
        $attribute->addOption('green');
        $this->assertEquals(array('red','blue','green'),$attribute->options(),'should add multiple options');
    }

    function testShouldSetValue()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $attribute->addOption('blue');
        $attribute->setValue('red');
        $this->assertEquals('red',$attribute->value(),'should set value');
    }

    function testShouldNotHavePriceModifier()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $attribute->setValue('red');
        $this->assertFalse($attribute->hasPriceModifier(), 'should not have price modifier');
    }

    function testShouldHavePriceModifier()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'flat_fee'=>5
        ));
        $attribute->setValue('red');
        $this->assertTrue($attribute->hasPriceModifier(), 'should have price modifier');
    }

    function testShouldNotModifyPrice()
    {
        $attribute = new Attribute;
        $attribute->addOption('red');
        $attribute->setValue('red');
        $price = $attribute->modifyPrice(5);
        $this->assertEquals(5, $price, 'should not modify price');
    }

    function testShouldModifyPriceFlatFee()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'flat_fee'=>5
        ));
        $attribute->setValue('red');
        $price = $attribute->modifyPrice(5);
        $this->assertEquals(10, $price, 'should modify price by flat fee');
    }

    function testShouldModifyPricePercentage()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'percentage'=>10
        ));
        $attribute->setValue('red');
        $price = $attribute->modifyPrice(10);
        $this->assertEquals(11, $price, 'should modify price by percentage');
    }

    /**
     * @expectedException Exception
     */
    function testShouldDisallowInvalidValue()
    {
        $attribute = new Attribute;
        $attribute->setValue('invalid');
    }

}