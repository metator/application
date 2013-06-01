<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\Attribute;
use \Application\AttributeMapper;
class AttributeMapperTest extends PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->db = Zend_Registry::get('db');
        $this->db->beginTransaction();
    }

    function tearDown()
    {
        $this->db->rollback();
    }

    function testShouldAssignAttributeId()
    {
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $mapper = new AttributeMapper($this->db);
        $id = $mapper->save($attribute);
        $this->assertTrue($id>0, 'should assign an attribute ID');
        $this->assertEquals($id, $attribute->id(), 'should assign attribute ID');
    }

    function testShouldSaveAttributeName()
    {
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $mapper = new AttributeMapper($this->db);
        $id = $mapper->save($attribute);
        $newAttribute = $mapper->load($id);
        $this->assertEquals($newAttribute->name(), 'Color', 'should save attribute name');
    }

    function testShouldSaveAttributeOptions()
    {
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $attribute->addOption('red');
        $attribute->addOption('blue');

        // save !
        $mapper = new AttributeMapper($this->db);
        $id = $mapper->save($attribute);

        $newAttribute = $mapper->load($id);
        $this->assertTrue($newAttribute->hasOption('red'), 'should save attribute options');
        $this->assertTrue($newAttribute->hasOption('blue'), 'should save attribute options');
    }

    function testShouldAssignOptionIds()
    {
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $attribute->addOption('red');
        $attribute->addOption('blue');

        // save !
        $mapper = new AttributeMapper($this->db);
        $mapper->save($attribute);

        $this->assertTrue($attribute->optionId('red')>0, 'should assign an attribute ID');
        $this->assertTrue($attribute->optionId('blue')>0, 'should assign an attribute ID');
    }

    function testShouldLoadOptionIds()
    {
        $attribute = new Attribute(array(
            'name'=>'Color'
        ));
        $attribute->addOption('red');
        $attribute->addOption('blue');

        // save !
        $mapper = new AttributeMapper($this->db);
        $id = $mapper->save($attribute);

        $loaded = $mapper->load($id);

        $this->assertTrue($loaded->optionId('red')>0, 'should assign an attribute ID');
        $this->assertTrue($loaded->optionId('blue')>0, 'should assign an attribute ID');
    }
}