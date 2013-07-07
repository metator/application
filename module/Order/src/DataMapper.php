<?php
namespace Metator\Order;

use Zend\Db\TableGateway\TableGateway;

/** Composes the address Data Mapper to save shopping cart contents with a billing/shipping address. */
class DataMapper
{
    protected $db, $table, $addressDataMapper;

    function __construct($db)
    {
        $this->db = $db;
        $this->table = new TableGateway('order', $this->db);
        $this->addressDataMapper = new \Metator\Address\DataMapper($this->db);
    }

    function save($order)
    {
        $this->addressDataMapper->save($order['shipping']);
        $this->addressDataMapper->save($order['billing']);
        $this->table->insert(array(
            'shipping'=>$order['shipping']['id'],
            'billing'=>$order['billing']['id'],
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
        return $order;
    }
}