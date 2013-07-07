<?php
namespace Metator\Cart;

use Zend\Db\TableGateway\TableGateway;

class DataMapper
{
    protected $db, $cartTable, $cartItemTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->cartTable = new TableGateway('cart', $this->db);
        $this->cartItemTable = new TableGateway('cart_item', $this->db);
    }

    function save(Cart $cart)
    {
        $this->cartTable->insert(array('id'=>null));
        $cart_id = $this->cartTable->getLastInsertValue();

        foreach($cart->items() as $item_id) {
            $this->cartItemTable->insert(array(
                'cart_id'=>$cart_id,
                'item_id'=>$item_id
            ));
        }
        return $cart_id;
    }

    function load($id)
    {
        $rowset = $this->cartTable->select(array(
            'id'=>$id
        ));
        $cart = (array)$rowset->current();
        $cart = new Cart;

        $rowset = $this->cartItemTable->select(array(
            'cart_id'=>$id
        ));
        $cartItems = (array)$rowset->current();
        foreach($cartItems as $cartItemsRow) {
            $cart->add((int)$cartItemsRow);
        }
        return $cart;
    }
}