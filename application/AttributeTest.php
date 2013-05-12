<?php
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

    /**
     * @expectedException Exception
     */
    function testShouldDisallowInvalidValue()
    {
        $attribute = new Attribute;
        $attribute->setValue('invalid');
    }

}