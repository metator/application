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

    function testShouldSaveAttributes()
    {
        return $this->markTestIncomplete();
    }

    function testShouldSaveConfigurableAttribute()
    {
        return $this->markTestIncomplete();

        $product = new Product(array('name'=>'widget'));

        // add "color" attribute
        $color = new Attribute(array(
            'name'=>'Color'
        ));
        $color->addOption('red', array(
            'flat_fee'=>5
        ));
        $color->addOption('blue');

        // save attribute "color" !
        $attribute_mapper = new AttributeMapper($this->db);
        $color_attribute_id = $attribute_mapper->save($color);

        $product->addAttribute($color);

        // add "size" attribute
        $size = new Attribute(array(
            'name'=>'Size'
        ));
        $size->addOption('large', array(
            'percentage'=>10
        ));
        $product->addAttribute($size);

        // save attribute "color" !
        $attribute_mapper = new AttributeMapper($this->db);
        $size_color_id = $attribute_mapper->save($size);

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertTrue($new_product->hasAttribute('color'), 'should save product with color attribute');
        $this->assertEquals(array('red','blue'),$new_product->attribute('color')->options(), 'should save options for color');
        $this->assertEquals(5,$new_product->attribute('color')->priceModifierForOption('red')->flatFee(), 'should save price modifier for color');
        $this->assertTrue($new_product->hasAttribute('size'), 'should save product with color attribute');
        $this->assertEquals(array('large'),$new_product->attribute('color')->options(), 'should save options for size');
        $this->assertEquals(10,$new_product->attribute('color')->priceModifierForOption('red')->percentage(), 'should save price modifier for size');
    }
}