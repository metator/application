<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product;

use \Application\AttributeMapper;

class DataMapperTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->db = \phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
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
        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals('sku123', $new_product->getSku(), 'should save sku');
    }

    function testShouldAssignID()
    {
        $product = new Product(array('sku'=>'sku123'));

        // save !
        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);
        $this->assertEquals($id, $product->id(), 'should assign ID to product');
    }

    function testShouldSaveName()
    {
        $product = new Product(array('name'=>'widget'));

        // save !
        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals('widget', $new_product->getName(), 'should save name');
    }

    function testShouldUpdateName()
    {
        $product = new Product(array('name'=>'widget'));

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $product->setName('foobar');
        $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $this->assertEquals('foobar',$product->getName(), 'should update name of existing product');
    }

    function testShouldSaveDescription()
    {
        $product = new Product(array('description'=>'foo bar baz bat'));

        // save !
        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals('foo bar baz bat', $new_product->getDescription(), 'should save description');
    }

    function testShouldUpdateDescription()
    {
        $product = new Product(array('description'=>'foo bar baz bat'));

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $product->setDescription('new 123');
        $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $this->assertEquals('new 123',$product->getDescription(), 'should update description of existing product');
    }

    function testShouldSaveBasePrice()
    {
        $product = new Product(array('base_price'=>'12.34'));

        // save !
        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $new_product = $product_mapper->load($id);
        $this->assertEquals('12.34', $new_product->getBasePrice(), 'should save base price');
    }

    function testShouldUpdateBasePrice()
    {
        $product = new Product(array('base_price'=>'12.34'));

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $product->setBasePrice('12.45');
        $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $this->assertEquals('12.45',$product->getBasePrice(), 'should update base price of existing product');
    }

    function testShouldAssociateImageHash()
    {
        $image_hash = 'foobar123';

        $product = new Product(array());
        $product->addImageHash($image_hash);

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $this->assertEquals(array('foobar123'), $product->getImageHashes(), 'should associate image hashes');
    }

    function testShouldPreserveExistingImages()
    {
        $image_hash = 'foobar123';

        $product = new Product(array());
        $product->addImageHash($image_hash);

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $product = $product_mapper->load($id);
        $this->assertEquals(array('foobar123'), $product->getImageHashes(), 'should preserve existing image hashes');
    }

    function testShouldSetDefaultImageHash()
    {
        $product = new Product(array());
        $product->addImageHash('foo123');
        $product->addImageHash('foo456');
        $product->addImageHash('foo789');

        $product->setDefaultImageHash('foo456');

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);
        $product = $product_mapper->load($id);
        $this->assertEquals('foo456', $product->getDefaultImageHash(), 'should set default image hash');
    }

    function testShouldList()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1,'name'=>'foo')));
        $product_mapper->save(new Product(array('sku'=>2,'name'=>'bar')));
        $list = $product_mapper->find();
        $this->assertEquals(2,count($list),'should list all products');
        $this->assertEquals('foo',$list[0]->getName(),'should list 1st product');
        $this->assertEquals('bar',$list[1]->getName(),'should list 2nd product');
    }

    function testShouldCount()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1,'name'=>'foo')));
        $product_mapper->save(new Product(array('sku'=>2,'name'=>'bar')));
        $count = $product_mapper->count();
        $this->assertEquals(2, $count, 'should count all products');
    }

    function testShouldNotCountInactive()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1,'name'=>'foo')));
        $id = $product_mapper->save(new Product(array('sku'=>2,'name'=>'bar')));
        $product_mapper->deactivate($id);
        $count = $product_mapper->count();
        $this->assertEquals(1, $count, 'should count only active products');
    }

    function testShouldCountByCategory()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1, 'name'=>'foo', 'categories'=>[1])));
        $product_mapper->save(new Product(array('sku'=>2, 'name'=>'bar', 'categories'=>[1])));
        $product_mapper->save(new Product(array('sku'=>3, 'name'=>'bar', 'categories'=>[2])));
        $count = $product_mapper->countByCategory(1);
        $this->assertEquals(2, $count, 'should count products by category');
    }

    function testShouldNotCountInactiveByCategory()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1, 'name'=>'foo', 'categories'=>[1])));
        $id = $product_mapper->save(new Product(array('sku'=>2, 'name'=>'bar', 'categories'=>[1])));
        $product_mapper->deactivate($id);
        $count = $product_mapper->countByCategory(1);
        $this->assertEquals(1, $count, 'should count products by category');
    }

    function testShouldListPaginatedOffset()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1, 'name'=>'foo1')));
        $product_mapper->save(new Product(array('sku'=>2, 'name'=>'foo2')));
        $product_mapper->save(new Product(array('sku'=>3, 'name'=>'foo3')));
        $product_mapper->save(new Product(array('sku'=>4, 'name'=>'foo4')));
        $product_mapper->save(new Product(array('sku'=>5, 'name'=>'foo5')));
        $list = $product_mapper->find(array(), 1,3);

        $this->assertEquals(3,count($list),'should list products');
        $this->assertEquals('foo2',$list[0]->getName(),'should list 2nd product');
        $this->assertEquals('foo3',$list[1]->getName(),'should list 3rd product');
        $this->assertEquals('foo4',$list[2]->getName(),'should list 4th product');
    }

    function testShouldListPaginatedOffsetFrom0()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1, 'name'=>'foo1')));
        $product_mapper->save(new Product(array('sku'=>2, 'name'=>'foo2')));
        $product_mapper->save(new Product(array('sku'=>3, 'name'=>'foo3')));
        $product_mapper->save(new Product(array('sku'=>4, 'name'=>'foo4')));
        $product_mapper->save(new Product(array('sku'=>5, 'name'=>'foo5')));
        $list = $product_mapper->find(array(), 0,1);

        $this->assertEquals(1,count($list),'should list products');
        $this->assertEquals('foo1',$list[0]->getName(),'should list 1st product');
    }

    function testShouldSaveAttribute()
    {
        $product = new Product(array('name'=>'widget'));
        $product->setAttributeValue('color', 'red');

        $product_mapper = new DataMapper($this->db);
        $id = $product_mapper->save($product);

        $loaded_product = $product_mapper->load($id);
        $this->assertEquals('red', $loaded_product->attributeValue('color'), 'should save attribute');
    }

    function testShouldFindBySKU()
    {
        $product = new Product(array('sku'=>'sku123'));

        // save !
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save($product);

        $new_product = $product_mapper->findBySku('sku123');
        $this->assertEquals('sku123', $new_product->getSku(), 'should find by sku');
    }

    function testShouldNotFindBySKU()
    {
        $product = new Product(array('sku'=>'foo'));

        // save !
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save($product);

        $this->assertFalse($product_mapper->findBySku('bar'), 'should not find a non-existent sku');
    }

    function testProductShouldNotExist()
    {
        $product = new Product(array('sku'=>'foo'));
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save($product);
        $this->assertFalse($product_mapper->productExists('bar'),   'product should not exist');
    }

    function testProductShouldExist()
    {
        $product = new Product(array('sku'=>'foo'));
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save($product);
        $this->assertTrue($product_mapper->productExists('foo'),    'product should exist');
    }

    function testShouldNotUpdateOtherProducts()
    {
        $product1 = new Product(array('sku'=>'foo1'));
        $product2 = new Product(array('sku'=>'foo2'));

        $product_mapper = new DataMapper($this->db);
        $product_mapper->save($product1);
        $product_mapper->save($product2);

        $product1->setName('bar');
        $product_mapper->save($product1);

        $product2 = $product_mapper->load($product2->id());
        $this->assertNotEquals('bar', $product2->getName());
    }

    function testShouldAssignToCategories()
    {
        $product = new Product(array('sku'=>'foo2'));
        $product->setCategories(array(1,2,3));
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save($product);

        $product = $product_mapper->load($product->id());
        $this->assertEquals(array(1,2,3), $product->getCategories(), 'should assign product to categories');
    }

    function testShouldUpdateCategories()
    {
        $product_mapper = new DataMapper($this->db);

        $product = new Product(array('sku'=>'foo2'));
        $product->setCategories(array(1,2,3));
        $product_mapper->save($product);

        $product = $product_mapper->load($product->id());
        $product->setCategories(array(1,2));
        $product_mapper->save($product);

        $this->assertEquals(array(1,2), $product->getCategories(), 'should update product categories');
    }

    function testShouldFindByCategory()
    {
        $product_mapper = new DataMapper($this->db);

        $product = new Product(array('sku'=>'foo2'));
        $product->setCategories(array(1));
        $product_mapper->save($product);

        $products = $product_mapper->findByCategory(1);

        $this->assertEquals(1,count($products));
        $this->assertEquals($product->id(), $products[0]->id(), 'should find products by category');
    }

    function testShouldPaginateFindByCategory()
    {
        $product_mapper = new DataMapper($this->db);
        $product_mapper->save(new Product(array('sku'=>1, 'name'=>'foo1', 'categories'=>[77])));
        $product_mapper->save(new Product(array('sku'=>2, 'name'=>'foo2', 'categories'=>[77])));
        $product_mapper->save(new Product(array('sku'=>3, 'name'=>'foo3', 'categories'=>[77])));
        $product_mapper->save(new Product(array('sku'=>4, 'name'=>'foo4', 'categories'=>[77])));
        $product_mapper->save(new Product(array('sku'=>5, 'name'=>'foo5', 'categories'=>[77])));
        $list = $product_mapper->findByCategory(77, 1, 3);

        $this->assertEquals(3,count($list),'should list products');
        $this->assertEquals('foo2',$list[0]->getName(),'should list 2nd product');
        $this->assertEquals('foo3',$list[1]->getName(),'should list 3rd product');
        $this->assertEquals('foo4',$list[2]->getName(),'should list 4th product');
    }

    function testShouldDeactivate()
    {
        $product_mapper = new DataMapper($this->db);

        $product = new Product(array('sku'=>'foo2'));
        $product_mapper->save($product);

        $product_mapper->deactivate($product->id());
        $products = $product_mapper->find(['active'=>1]);
        $this->assertEquals(0, count($products), 'should deactivate product');
    }

    function testFindingByCategoryShouldFindOnlyActive()
    {
        $product_mapper = new DataMapper($this->db);

        $product = new Product(array('sku'=>'foo2'));
        $product->setCategories(array(1));
        $product_mapper->save($product);

        $product_mapper->deactivate($product->id());

        $products = $product_mapper->findByCategory(1);

        $this->assertEquals(0,count($products));
    }

    function testShouldFindByAttributeValue()
    {
        $product_mapper = new DataMapper($this->db);

        $product = new Product(array('sku'=>'foo'));
        $product->setAttributeValue('color','red');
        $product_mapper->save($product);

        $product_mapper->deactivate($product->id());
        $products = $product_mapper->find(array(
            'attributes'=>['color'=>'red']
        ));
        $this->assertEquals(1, count($products), 'should find by attribute');
    }
}