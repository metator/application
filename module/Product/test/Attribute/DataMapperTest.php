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

    function testShouldListAttributes()
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
        $this->assertEquals(['color','size'], $attribute_mapper->listAttributes(), 'should list attributes');
    }
}