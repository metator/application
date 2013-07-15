<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

use Metator\Product\Importer;

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

    function testShouldUseBatchQuery()
    {
        $profiler = $this->db->getProfiler();
        $queryProfiles = $profiler->getQueryProfiles();
        $beforeCount = count($queryProfiles);

        $csv = $this->generateCSV(100);

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $queryProfiles = $profiler->getQueryProfiles();
        $afterCount = count($queryProfiles);

        $this->assertLessThanOrEqual(15, $afterCount-$beforeCount, 'should use <=15 queries to import >=100 products');
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
        `php ./metator sample products --number=5,000`;
        $end = microtime(true);
        $this->assertLessThan(10, $end-$start, 'should import 5K products in < 10s');
    }

    function generateCSV($lines)
    {
        $csv = "sku,name\n";
        for($i=1; $i<=$lines; $i++) {
            $csv.= "sku-$i,name-$i\n";
        }
        return $csv;
    }
}