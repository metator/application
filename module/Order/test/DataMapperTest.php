<?php
namespace Metator\Order;

class DataMapperTest extends \PHPUnit_Framework_TestCase
{
    protected $db, $product1, $product2, $product1_id, $product2_id;

    function setUp()
    {
        $this->db = \phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $this->db->getDriver()->getConnection()->beginTransaction();
    }

    function tearDown()
    {
        $this->db->getDriver()->getConnection()->rollback();
    }

    function testShouldReturnOrderID()
    {
        $order = array();
        $orderMapper = new DataMapper($this->db);
        $id = $orderMapper->save($order);
        $this->assertNotEquals(0, $id, 'should assign an order ID');
    }

    function testShouldSaveAPIReference()
    {
        $order = array(
            'api_reference'=>'123456789_0-ABC'
        );
        $orderMapper = new DataMapper($this->db);
        $id = $orderMapper->save($order);
        $reloaded_order = $orderMapper->load($id);
        $this->assertEquals('123456789_0-ABC', $reloaded_order['api_reference'], 'should save the reference returned from the API success response');
    }

    function testShouldCreateNewAddressesAndOrder()
    {
        $shipping_address = array(
            'first_name' => 'Joshua',
            'last_name' => 'Ribakoff',
            'email' => 'josh.ribakoff@gmail.com',
            'address' => '123 Test St',
            'address2' => 'Suite 5',
            'city' => 'Port St Lucie',
            'state' => 'FL',
            'postal' => '00123',
            'country' => 'USA',
            'phone' => '0101010101',
            'fax' => '0202020202',
        );

        $billing_address = array(
            'first_name' => 'Joshua-different',
            'last_name' => 'Ribakoff-different',
            'email' => 'josh.ribakoff-different@gmail.com',
            'address' => '123 Test St-different',
            'address2' => 'Suite 5-different',
            'city' => 'Port St Lucie-different',
            'state' => 'FL-different',
            'postal' => '12345',
            'country' => 'USA-different',
            'phone' => '111111111',
            'fax' => '2222222222',
        );

        $order = array(
            'shipping'=>$shipping_address,
            'billing'=>$billing_address,
            'created'=>'0000-00-00 00:00:00'
        );

        $orderMapper = new DataMapper($this->db);
        $id = $orderMapper->save($order);

        $reloaded_order = $orderMapper->load($id);

        $expected = $order;
        $expected['id'] = $id;
        $expected['billing']['id'] = $reloaded_order['billing']['id'];
        $expected['shipping']['id'] = $reloaded_order['shipping']['id'];
        $expected['api_reference'] = '';

        $this->assertEquals($expected, $reloaded_order, 'should save new order w/ new addresses');
    }

    function testShouldSaveCartAndItems()
    {
        $cart = new \Metator\Cart\Cart;
        $cart->add(1, 9.99);
        $cart->add(2, 4.99);
        $cart->setQuantity(2, 2);

        $order = array(
            'items'=>$cart,
            'created'=>'0000-00-00 00:00:00'
        );

        $orderMapper = new DataMapper($this->db);
        $id = $orderMapper->save($order);

        $reloaded_order = $orderMapper->load($id);

        $this->assertEquals(array(1,2), $reloaded_order['items']->items(), 'should save items');
    }
}