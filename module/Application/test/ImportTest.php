<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\Importer;
use Metator\Product\DataMapper as ProductDataMapper;
class ImportTest extends PHPUnit_Framework_TestCase
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

    function testShouldImportCategoryId()
    {
        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $id = $categoryMapper->save(['name'=>'test']);

        $csv = "sku,name,base_price,attributes,categories\n";
        $csv.= "123,name,0,null,$id";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertEquals(array($id), $this->findProductBySku('123')->getCategories());
    }

    function testShouldImportMultipleCategoryId()
    {
        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $id1 = $categoryMapper->save(['name'=>'test1']);
        $id2 = $categoryMapper->save(['name'=>'test2']);

        $csv = "sku,name,base_price,attributes,categories\n";
        $csv.= "123,name,0,null,$id1;$id2";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertEquals(array($id1,$id2), $this->findProductBySku('123')->getCategories());
    }

    function testShouldImportCategoryByName()
    {
        return $this->markTestIncomplete();

        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $id = $categoryMapper->save(['name'=>'test']);

        $csv = "sku,name,base_price,attributes,categories\n";
        $csv.= '123,name,0,null,test';

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $categories = $categoryMapper->findAll();

        $this->assertEquals(array($id), $this->findProductBySku('123')->getCategories());
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
        $csv = "sku,name\n";
        $csv.= "123,name\n";
        $csv.= "456,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $csv = "sku,name\n";
        $csv.= "789,name\n";
        $csv.= "abc,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $product_mapper = new ProductDataMapper($this->db);
        $products = $product_mapper->find();

        $this->assertEquals(4, count($products), 'should not re-import previous imports (should cleanup import table when done');
    }

    function productExists($sku)
    {
        $product_mapper = new ProductDataMapper($this->db);
        return $product_mapper->productExists($sku);
    }

    function findProductBySku($sku)
    {
        $product_mapper = new ProductDataMapper($this->db);
        return $product_mapper->find(['sku'=>$sku])[0];
    }
}