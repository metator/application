<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\Importer;
use \Application\ProductMapper;
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

    function testImportProductAttributes()
    {
        return $this->markTestIncomplete();

        $csv = "sku,attributes\n";
        $csv.= '123,{"color":["red","blue"]}';

        $importer = new Importer;
        $importer->import($csv);

        $this->assertTrue(false);
    }

    function testShouldImportPriceModifiers()
    {
        return $this->markTestIncomplete();


        $csv = "sku,attributes\n";
        $csv.= '123,{"color":{"red":"+$5","blue":"+10%"}}';

        $importer = new Importer;
        $importer->import($csv);

        $this->assertTrue(false);
    }

    function testShouldImportDynamicSku()
    {
        return $this->markTestIncomplete();
        $csv = "sku,attributes,dynamic_sku\n";
        $csv.= '123,';

        // 'attributes' field is json structure representing possible options
        $csv.='{"color":["red","blue"], "size":["small","large"]},';

        // 'dynamic_sku' could be the SKU that gets saved on the sales order, matches each permutation of attributes as read left to right
        $csv.='["red-small","red-large","blue-small","blue-large"]';

        // the SKUs could be anything they mapped to a combination of attributes based on specified order
        $csv.='["rs","rl","bs","bl"]';

        // basically the first dynamic_sku is the 1st value from the 1st & 2nd attributes
        // the next one is the 1st from the 1st attribute, & 2nd value from the 2nd attribute
        // the next one is the 2nd value from the 1st attribute, & 1st value from 2nd attribute
        // the next one is the 2nd value from the 1st attribute, & 2nd value from 2nd attribute

        // if there were 3 attributes with 3 values the dynamic_sku would be an array of the SKU for

        //  the 1st value from the 1st 2nd, & 3rd attributes
        // the next one is the 1st from the 1st attribute, 1st value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 1st from the 1st attribute, 1st value from the 2nd attribute & and 3nd value from 3rd
        // the next one is the 1st from the 1st attribute, 2nd value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 1st from the 1st attribute, 2nd value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 1st from the 1st attribute, 2nd value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 1st from the 1st attribute, 3rd value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 1st from the 1st attribute, 3rd value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 1st from the 1st attribute, 3rd value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 2nd from the 1st attribute, 1st value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 2nd from the 1st attribute, 1st value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 2nd from the 1st attribute, 1st value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 2nd from the 1st attribute, 2nd value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 2nd from the 1st attribute, 2nd value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 2nd from the 1st attribute, 2nd value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 2nd from the 1st attribute, 3rd value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 2nd from the 1st attribute, 3rd value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 2nd from the 1st attribute, 3rd value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 3rd from the 1st attribute, 1st value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 3rd from the 1st attribute, 1st value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 3rd from the 1st attribute, 1st value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 3rd from the 1st attribute, 2nd value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 3rd from the 1st attribute, 2nd value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 3rd from the 1st attribute, 2nd value from the 2nd attribute & and 3rd value from 3rd
        // the next one is the 3rd from the 1st attribute, 3rd value from the 2nd attribute & and 1st value from 3rd
        // the next one is the 3rd from the 1st attribute, 3rd value from the 2nd attribute & and 2nd value from 3rd
        // the next one is the 3rd from the 1st attribute, 3rd value from the 2nd attribute & and 3rd value from 3rd

    }

    function productExists($sku)
    {
        $product_mapper = new ProductMapper($this->db);
        return $product_mapper->productExists($sku);
    }
}