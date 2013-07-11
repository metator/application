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
        $this->db->query("truncate `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("delete from `product`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    function testShouldImportProduct()
    {
        $csv = "sku,name\n";
        $csv.= "123,name";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $this->assertTrue($this->productExists('123'), 'should import product');
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
}