<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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
                'item_id'=>$item_id,
                'price'=>$cart->price($item_id),
                'quantity'=>$cart->quantity($item_id)
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
        while($item = $rowset->current()) {
            $cart->add((int)$item['item_id'], (float)$item['price']);
            $cart->setQuantity($item['item_id'], $item['quantity']);
        }
        return $cart;
    }
}