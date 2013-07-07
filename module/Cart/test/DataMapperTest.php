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
        $cart->add(1);
        $cart->add(2);
        $id = $this->dataMapper->save($cart);
        $reloaded_cart = $this->dataMapper->load($id);
        $this->assertEquals(array(1,2), $reloaded_cart->items(), 'should save cart item IDs');
    }

    function testShouldSaveItemQuantity()
    {
        return $this->markTestIncomplete();
    }

    function testShouldSaveItemPrices()
    {
        $cart = new \Metator\Cart\Cart;
        $cart->add(1, 9.99);
        $cart->add(2, 4.99);
        $id = $this->dataMapper->save($cart);
        $reloaded_cart = $this->dataMapper->load($id);
        $this->assertEquals(9.99, $reloaded_cart->price(1), 'should save item price');
        $this->assertEquals(4.99, $reloaded_cart->price(2), 'should save item price');
    }

}