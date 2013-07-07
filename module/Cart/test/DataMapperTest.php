<?php
namespace Metator\Cart;

class DataMapperTest extends \PHPUnit_Framework_TestCase
{
    protected $db;

    function setUp()
    {
        $this->db = \phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $this->db->getDriver()->getConnection()->beginTransaction();
        $this->dataMapper = new DataMapper($this->db);
    }

    function tearDown()
    {
        $this->db->getDriver()->getConnection()->rollback();
    }

    function testShouldSaveID()
    {
        $cart = new Cart;
        $id = $this->dataMapper->save($cart);
        $this->assertNotEquals(0, $id, 'should create an ID');
    }

    function testShouldSaveItems()
    {
        $cart = new \Metator\Cart\Cart;
        $cart->add(1, 9.99);
        $cart->add(2, 4.99);
        $id = $this->dataMapper->save($cart);
        $reloaded_cart = $this->dataMapper->load($id);
        $this->assertEquals(array(2,1), $reloaded_cart->items(), 'should save cart item IDs');
    }

    function testShouldSaveItemQuantity()
    {
        return $this->markTestIncomplete();
    }

    function testShouldSaveItemPrices()
    {
        return $this->markTestIncomplete();
    }

}