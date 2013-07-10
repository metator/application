<?php
namespace Metator\Order;

use Zend\Db\TableGateway\TableGateway;

/** Composes the address Data Mapper to save shopping cart contents with a billing/shipping address. */
class DataMapper
{
    protected $db, $table, $addressDataMapper, $cartDataMapper;

    function __construct($db)
    {
        $this->db = $db;
        $this->table = new TableGateway('order', $this->db);
        $this->addressDataMapper = new \Metator\Address\DataMapper($this->db);
        $this->cartDataMapper = new \Metator\Cart\DataMapper($this->db);
    }

    function findAll()
    {
        $rowset = $this->table->select();
        $orders = array();
        while($row = $rowset->current()) {
            array_push($orders, $this->load($row['id']));
        }
        return $orders;
    }

    function save($order)
    {
        if(isset($order['shipping'])) {
            $this->addressDataMapper->save($order['shipping']);
        }
        if(isset($order['billing'])) {
            $this->addressDataMapper->save($order['billing']);
        }
        if(isset($order['items'])) {
            $order['cart_id'] = $this->cartDataMapper->save($order['items']);
            unset($order['items']);
        }
        $this->table->insert(array(
            'shipping'=>isset($order['shipping']) ? $order['shipping']['id'] : null,
            'billing'=>isset($order['billing']) ? $order['billing']['id'] : null,
            'cart_id'=>isset($order['cart_id']) ? $order['cart_id'] : 0,
            'api_reference'=>isset($order['api_reference']) ? $order['api_reference'] : '',
        ));
        return $this->table->getLastInsertValue();
    }

    function load($id)
    {
        $rowset = $this->table->select(array(
            'id'=>$id
        ));
        $order = (array)$rowset->current();

        $order['shipping'] = $this->addressDataMapper->load($order['shipping']);
        $order['billing'] = $this->addressDataMapper->load($order['billing']);
        if($order['cart_id']) {
            $order['items'] = $this->cartDataMapper->load($order['cart_id']);
        }
        unset($order['cart_id']);
        return $order;
    }
}