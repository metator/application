<?php
class Product
{
    protected $name;

    function __construct($params=array())
    {
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->price = isset($params['price']) ? $params['price'] : '';
    }

    function name()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function price()
    {
        return $this->price;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }
}