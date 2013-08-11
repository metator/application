<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

use Metator\Product\Importer;
use Metator\Product\DataMapper as ProductDataMapper;

class Import_CategoryTest extends PHPUnit_Framework_TestCase
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

    function testShouldAddNewCategoryByName()
    {
        $csv = "sku,name,base_price,attributes,categories\n";
        $csv.= '123,name,0,null,test';

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $categories = $categoryMapper->findAll();

        $this->assertEquals('test', $categories[0]['name'], 'should add a new category by name');
    }

    function testShouldImportExistingCategoryByName()
    {
        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $id = $categoryMapper->save(['name'=>'test']);

        $csv = "sku,name,base_price,attributes,categories\n";
        $csv.= '123,name,0,null,test';

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertEquals(array($id), $this->findProductBySku('123')->getCategories(), 'should import existing category by name');
    }

    function testShouldNotCreateDuplicateCategory()
    {
        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $id = $categoryMapper->save(['name'=>'test']);

        $csv = "sku,name,base_price,attributes,categories\n";
        $csv.= '123,name,0,null,test';

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $categories = $categoryMapper->findAll();
        $this->assertEquals(1, count($categories), 'should not create duplicate categories');
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