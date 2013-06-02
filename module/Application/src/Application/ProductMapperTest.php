<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\Product;
use \Application\ProductMapper;
use \Application\AttributeMapper;
class ProductMapperTest extends PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->db = phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $this->db->getDriver()->getConnection()->beginTransaction();
    }

    function tearDown()
    {
        $this->db->getDriver()->getConnection()->rollback();
    }

    function testShouldSaveSku()
    {
        $product = new Product(array('sku'=>'sku123'));

        // save !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals('sku123', $new_product->getSku(), 'should save sku');
    }

    function testShouldAssignID()
    {
        $product = new Product(array('sku'=>'sku123'));

        // save !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);
        $this->assertEquals($id, $product->id(), 'should assign ID to product');
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

    function testShouldUpdateName()
    {
        $product = new Product(array('name'=>'widget'));

        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $product->setName('foobar');
        $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $this->assertEquals('foobar',$product->name(), 'should update name of existing product');
    }

    function testShouldList()
    {
        $product_mapper = new ProductMapper($this->db);
        $product_mapper->save(new Product(array('name'=>'foo')));
        $product_mapper->save(new Product(array('name'=>'bar')));
        $list = $product_mapper->find();
        $this->assertEquals(2,count($list),'should list all products');
        $this->assertEquals('foo',$list[0]->name(),'should list 1st product');
        $this->assertEquals('bar',$list[1]->name(),'should list 2nd product');
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

    function testShouldSaveConfigurableAttributeFlatFee()
    {
        $product = new Product(array('name'=>'widget'));

        // add "color" attribute
        $product->addAttribute('color',array(
            'options'=>array(
                'red' => array('flat_fee'=>5)
            )
        ));

        // save attribute "color" !
        $attribute_mapper = new AttributeMapper($this->db);
        $attribute_mapper->save($product->attribute('color'));

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals(5,$new_product->priceModifierFor('color','red')->flatFee(), 'should save price modifier');
    }

    function testShouldSaveConfigurableAttributePercentage()
    {
        $product = new Product(array('name'=>'widget'));

        // add "color" attribute
        $product->addAttribute('color',array(
            'options'=>array(
                'red' => array('percentage'=>5)
            )
        ));

        // save attribute "color" !
        $attribute_mapper = new AttributeMapper($this->db);
        $attribute_mapper->save($product->attribute('color'));

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals(5,$new_product->priceModifierFor('color','red')->percentage(), 'should save price modifier');
    }

    function testShouldSaveConfigurableAttributesMultiple()
    {
        $product = new Product(array('name'=>'widget'));

        // add "color" attribute
        $product->addAttribute('color',array(
            'options'=>array(
                'red' => array('percentage'=>5),
                'blue'=> array('flat_fee'=>8)
            )
        ));

        // save attribute "color" !
        $attribute_mapper = new AttributeMapper($this->db);
        $attribute_mapper->save($product->attribute('color'));

        // add "size" attribute
        $product->addAttribute('size',array(
            'options'=>array(
                'small' => array('percentage'=>5),
                'large'=> array('flat_fee'=>8)
            )
        ));

        // save attribute "size" !
        $attribute_mapper = new AttributeMapper($this->db);
        $attribute_mapper->save($product->attribute('size'));

        // save product !
        $product_mapper = new ProductMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals(5,$new_product->priceModifierFor('color','red')->percentage(), 'should save price modifier');
        $this->assertEquals(8,$new_product->priceModifierFor('color','blue')->flatFee(), 'should save price modifier');
        $this->assertEquals(5,$new_product->priceModifierFor('size','small')->percentage(), 'should save price modifier');
        $this->assertEquals(8,$new_product->priceModifierFor('size','large')->flatFee(), 'should save price modifier');
    }

    function testShouldFindBySKU()
    {
        $product = new Product(array('sku'=>'sku123'));

        // save !
        $product_mapper = new ProductMapper($this->db);
        $product_mapper->save($product);

        $new_product = $product_mapper->findBySku('sku123');
        $this->assertEquals('sku123', $new_product->getSku(), 'should find by sku');
    }

    function testShouldNotFindBySKU()
    {
        $product = new Product(array('sku'=>'foo'));

        // save !
        $product_mapper = new ProductMapper($this->db);
        $product_mapper->save($product);

        $this->assertFalse($product_mapper->findBySku('bar'), 'should not find a non-existent sku');
    }

    function testProductShouldNotExist()
    {
        $product = new Product(array('sku'=>'foo'));
        $product_mapper = new ProductMapper($this->db);
        $product_mapper->save($product);
        $this->assertFalse($product_mapper->productExists('bar'),   'product should not exist');
    }

    function testProductShouldExist()
    {
        $product = new Product(array('sku'=>'foo'));
        $product_mapper = new ProductMapper($this->db);
        $product_mapper->save($product);
        $this->assertTrue($product_mapper->productExists('foo'),    'product should exist');
    }

}