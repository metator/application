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
        $this->db->getDriver()->getConnection()->beginTransaction();
    }

    function tearDown()
    {
        $this->db->getDriver()->getConnection()->rollback();
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

    function testShouldUseSingleQuery()
    {
        $profiler = $this->db->getProfiler();
        $queryProfiles = $profiler->getQueryProfiles();
        $beforeCount = count($queryProfiles);

        $csv = "sku,name\n";
        $csv.= "123,name\n";
        $csv.= "456,name\n";
        $csv.= "789,name\n";
        $csv.= "111,name\n";
        $csv.= "222,name\n";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $queryProfiles = $profiler->getQueryProfiles();
        $afterCount = count($queryProfiles);

        $this->assertLessThan(2, $afterCount-$beforeCount, 'should use less than 2 queries to import >5 products');
    }

    function productExists($sku)
    {
        $product_mapper = new ProductDataMapper($this->db);
        return $product_mapper->productExists($sku);
    }
}