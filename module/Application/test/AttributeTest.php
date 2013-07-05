<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Application;
class AttributeTest extends \PHPUnit_Framework_TestCase
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

    function testShouldAddOptionsThroughConstructor()
    {
        $attribute = new Attribute(array(
            'options'=>array('red','blue','green')
        ));
        $this->assertEquals(array('red','blue','green'),$attribute->options(),'should add options through constructor');
    }

}