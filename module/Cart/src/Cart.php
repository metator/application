<?php
namespace Metator\Cart;

class Cart
{
    protected $items = array();
    protected $quantity = array();

    function add($id)
    {
        if(!in_array($id, $this->items)) {
            $this->items[] = $id;
        }
        if(!isset($this->quantity[$id])) {
            $this->quantity[$id] = 0;
        }
        $this->quantity[$id]++;
    }

    function remove($id)
    {
        $this->quantity[$id] = 0;
        $this->items = array_diff($this->items, array($id));
    }

    function items()
    {
        return $this->items;
    }

    function quantity($id)
    {
        return $this->quantity[$id];
    }

    function setQuantity($id, $quantity)
    {
        if($quantity == 0) {
            return $this->remove($id);
        }
        $this->quantity[$id] = $quantity;
    }

}