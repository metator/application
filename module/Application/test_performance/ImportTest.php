<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\Importer;
use Metator\Product\DataMapper as ProductDataMapper;
class ImportPerformanceTest extends PHPUnit_Framework_TestCase
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
        $csv.= "333,name\n";
        $csv.= "444,name\n";
        $csv.= "555,name\n";
        $csv.= "777,name\n";
        $csv.= "888,name\n";

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $queryProfiles = $profiler->getQueryProfiles();
        $afterCount = count($queryProfiles);

        $this->assertLessThan(7, $afterCount-$beforeCount, 'should use <=6 queries to import >=10 products');
    }

    function testShouldUpdateCount()
    {
        $profiler = $this->db->getProfiler();
        $queryProfiles = $profiler->getQueryProfiles();
        $beforeCount = count($queryProfiles);

        `php ./metator sample products --number=1000`;
        $product_mapper = new \Metator\Product\DataMapper($this->db);
        $count = $product_mapper->count();
        $this->assertEquals(1000, $count, 'should update count');

        $queryProfiles = $profiler->getQueryProfiles();
        $afterCount = count($queryProfiles);

        $this->assertLessThan(2, $afterCount-$beforeCount, 'should count products with 1 query');
    }

    function testShouldImport25kProductsQuickly()
    {
        $start = microtime(true);
        `php ./metator sample products --number=25,000`;
        $end = microtime(true);
        $this->assertLessThan(10, $end-$start, 'should import 25K products in less than a few seconds');
    }
}