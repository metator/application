<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Metator\Category;

class ImportTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->db = \phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
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

    function testShouldImportName()
    {
        $csv = "name\n";
        $csv.= 'foo';

        $importer = new Importer($this->db);
        $importer->importFromText($csv);

        $categoryMapper = new \Metator\Category\DataMapper($this->db);
        $categories = $categoryMapper->findAll();

        $this->assertEquals('foo', $categories[0]['name'], 'should add a new category by name');
    }
}