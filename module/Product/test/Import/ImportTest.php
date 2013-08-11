<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

use Metator\Product\Importer;
use Metator\Product\DataMapper as ProductDataMapper;

class Import_ImportTest extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->db = phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $this->emptyTables();
    }

    function tearDown()
    {
        $this->emptyTables();
    }

    function emptyTables()
    {
        // seems like LOAD DATA INFILE commits the transaction, so we must manually clean up the tables :/
        $this->db->query("truncate `product_import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("truncate `product_categories_import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("delete from `product`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("delete from `category`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("delete from `product_categories`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    function testShouldImportSku()
    {
        $csv = "sku,name\n";
        $csv.= "123,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertTrue($this->productExists('123'), 'should import product');
    }

    function testShouldImportDifferentOrder()
    {
        $csv = "name,sku\n";
        $csv.= "name,123";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertTrue($this->productExists('123'), 'should import fields in any order');
    }

    function testShouldImportActiveFlag()
    {
        $csv = "sku,name,active\n";
        $csv.= "123,name1,1\n";
        $csv.= "456,name2,0";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $products = $this->productDataMapper()->find(['active'=>1]);
        $this->assertEquals(1, count($products), 'should import active flag');
    }

    function testShouldImportName()
    {
        $csv = "sku,name\n";
        $csv.= "123,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertEquals('name', $this->findProductBySku('123')->getName(), 'should import name');
    }

    function testShouldImportPrice()
    {
        $csv = "sku,name,base_price\n";
        $csv.= "123,name,9.99";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertEquals(9.99, $this->findProductBySku('123')->getBasePrice(), 'should import price');
    }

    function testShouldImportAttributes()
    {
        $csv = "sku,name,base_price,attributes\n";
        $csv.= '123,name,0,{"color":"red"}';

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertEquals('red', $this->findProductBySku('123')->attributeValue('color'), 'should import attribute');
    }

    function testShouldImportMultipleProduct()
    {
        $csv = "sku,name\n";
        $csv.= "123,name\n";
        $csv.= "456,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertTrue($this->productExists('123'), 'should import product');
    }

    function testShouldNotReImportPreviousImports()
    {
        $csv = "sku,name,categories\n";
        $csv.= "123,name,test\n";
        $csv.= "456,name,test";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $csv = "sku,name,categories\n";
        $csv.= "789,name,test2\n";
        $csv.= "abc,name,test2";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $products = $this->productDataMapper()->find();

        $this->assertEquals(4, count($products), 'should not re-import previous imports (should cleanup import table when done');
    }

    function testShouldNotChangeIDOfExistingSKUs()
    {
        $csv = "sku,name\n";
        $csv.= "123,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $products1 = $this->productDataMapper()->find();

        $csv = "sku,name\n";
        $csv.= "123,name-new";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $products2 = $this->productDataMapper()->find();
        $this->assertEquals($products1[0]->id(), $products2[0]->id(), 'should not change ID of existing products on re-import');
    }

    function testShouldUpdateNameOnReimport()
    {
        $csv = "sku,name\n";
        $csv.= "123,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $csv = "sku,name\n";
        $csv.= "123,name-new";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $products = $this->productDataMapper()->find();
        $this->assertEquals('name-new',$products[0]->getName(), 'should update values on re-import');
    }

    function productExists($sku)
    {
        return $this->productDataMapper()->productExists($sku);
    }

    /** @return \Metator\Product\Product */
    function findProductBySku($sku)
    {
        return $this->productDataMapper()->find(['sku'=>$sku])[0];
    }

    function productDataMapper()
    {
        return new ProductDataMapper($this->db);
    }
}