<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product\Attribute;

use \Metator\Product\DataMapper as ProductDataMapper;
use \Metator\Product\Attribute\DataMapper as AttributeDataMapper;
use \Metator\Product\Product as Product;

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

    function testShouldListAttribute()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));

        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();
        $this->assertEquals(['color'], $attribute_mapper->listAttributes(), 'should list attribute');
    }

    function testShouldAddNewAttributes()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $attribute_mapper = new AttributeDataMapper($this->db);

        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));
        $attribute_mapper->index();

        $product_mapper->save(new Product(array(
            'sku'=>'112',
            'attributes'=>['size'=>'medium']
        )));
        $attribute_mapper->index();

        $this->assertEquals(['color','size'], $attribute_mapper->listAttributes(), 'should add new attributes');
    }

    function testShouldNotListDuplicateAttributesFromMultipleProducts()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));
        $product_mapper->save(new Product(array(
            'sku'=>'112',
            'attributes'=>['color'=>'blue']
        )));

        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();
        $this->assertEquals(['color'], $attribute_mapper->listAttributes(), 'should not list duplicate attributes from multiple products');
    }

    function testShouldNotListDuplicateAttributesFromReindex()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));

        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();
        $attribute_mapper->index();
        $this->assertEquals(['color'], $attribute_mapper->listAttributes(), 'should not list duplicate attributes from reindexing');
    }

    function testShouldListValues()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));
        $product_mapper->save(new Product(array(
            'sku'=>'112',
            'attributes'=>['size'=>'medium']
        )));

        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();
        $this->assertEquals(['red'], $attribute_mapper->listValues('color'), 'should list values');
    }

    function testShouldListNewValues()
    {
        $product_mapper = new ProductDataMapper($this->db);

        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));
        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();

        $product_mapper->save(new Product(array(
            'sku'=>'112',
            'attributes'=>['color'=>'blue']
        )));
        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();

        $this->assertEquals(['blue','red'], $attribute_mapper->listValues('color'), 'should list new values');
    }

    function testShouldIndexMultipleValuesAtOnce()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $attribute_mapper = new AttributeDataMapper($this->db);

        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));

        $product_mapper->save(new Product(array(
            'sku'=>'112',
            'attributes'=>['color'=>'blue']
        )));
        $attribute_mapper->index();

        $this->assertEquals(['blue','red'], $attribute_mapper->listValues('color'), 'should index multiple values at once');
    }

    function testShouldNotDuplicateValuesOnReindex()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        )));

        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();
        $attribute_mapper->index();
        $this->assertEquals(['red'], $attribute_mapper->listValues('color'), 'should not duplicate values on reindex');
    }

    function testShouldFilterOneAttributeByAnother()
    {
        $product_mapper = new ProductDataMapper($this->db);
        $attribute_mapper = new AttributeDataMapper($this->db);

        $product_mapper->save(new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red','size'=>'small']
        )));
        $product_mapper->save(new Product(array(
            'sku'=>'112',
            'attributes'=>['color'=>'red','size'=>'medium']
        )));
        $product_mapper->save(new Product(array(
            'sku'=>'113',
            'attributes'=>['color'=>'blue','size'=>'small']
        )));
        $attribute_mapper->index();

        $this->assertEquals(['red'], $attribute_mapper->listValues('color',['size'=>'medium']), 'should list colors of products that have size=medium');
    }

    function testShouldRemoveValuesOnReindex()
    {
        $product = new Product(array(
            'sku'=>'111',
            'attributes'=>['color'=>'red']
        ));

        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save($product);

        $attribute_mapper = new AttributeDataMapper($this->db);
        $attribute_mapper->index();

        $product->setAttributeValue('color','blue');
        $product_mapper->save($product);
        $attribute_mapper->index();
        $this->assertEquals(['blue'], $attribute_mapper->listValues('color'), 'should remove values on reindex');
    }
}