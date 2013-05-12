<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductMapperTest extends PHPUnit_Framework_TestCase
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

    function testShouldSaveName()
    {
        $product = new Product(array('name'=>'widget'));

        // save !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals('widget', $new_product->name(), 'should save name');
    }

    function testShouldSaveAttribute()
    {
        $product = new Product(array('name'=>'widget'));
        $product->addAttribute('color');

        // save attribute
        $attribute_mapper = new AttributeMapper($this->db);
        $attribute_mapper->save($product->attribute('color'));

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $loaded_product = $product_mapper->load($id);
        $this->assertTrue( $loaded_product->hasAttribute('color'), 'should save attribute');
    }

    function testShouldSaveAttributeOptions()
    {
        $product = new Product(array('name'=>'widget'));
        $product->addAttribute('color',array(
            'options'=>array('red','blue')
        ));

        // save attribute
        $attribute_mapper = new AttributeMapper($this->db);
        $attribute_mapper->save($product->attribute('color'));

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $loaded_product = $product_mapper->load($id);
        $this->assertEquals(array('blue','red'), $loaded_product->attribute('color')->options(), 'should save attribute options');
    }

    function testShouldSaveConfigurableAttribute()
    {
        return $this->markTestIncomplete();
        $product = new Product(array('name'=>'widget'));

        // add "color" attribute
        $product->addAttribute('color',array(
            'options'=>array(
                'red' => array('flat_fee'=>5),
                'blue'
            )
        ));

        // save attribute "color" !
        $attribute_mapper = new AttributeMapper($this->db);
        $color_attribute_id = $attribute_mapper->save($product->attribute('color'));

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals(5,$new_product->attribute('color')->priceModifierForOption('red')->flatFee(), 'should save price modifier for color');
        $this->assertEquals(10,$new_product->attribute('color')->priceModifierForOption('red')->percentage(), 'should save price modifier for size');
    }
}