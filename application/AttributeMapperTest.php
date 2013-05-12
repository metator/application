<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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

    function testShouldSaveOptionFlatFee()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'flat_fee'=>10
        ));

        // save !
        $mapper = new AttributeMapper($this->db);
        $id = $mapper->save($attribute);
        $newAttribute = $mapper->load($id);

        $this->assertEquals(10, $newAttribute->priceModifierForOption('red')->flatFee(), 'should save flat_fee markup');
        $this->assertEquals(0,$newAttribute->priceModifierForOption('red')->percentage(), 'should not have percentage');
    }

    function testShouldSaveOptionPercentage()
    {
        $attribute = new Attribute;
        $attribute->addOption('red', array(
            'percentage'=>11
        ));

        // save !
        $mapper = new AttributeMapper($this->db);
        $id = $mapper->save($attribute);
        $newAttribute = $mapper->load($id);

        $this->assertEquals(11, $newAttribute->priceModifierForOption('red')->percentage(), 'should save percentage markup');
        $this->assertEquals(0,$newAttribute->priceModifierForOption('red')->flatFee(), 'should not have flat_fee');
    }
}

