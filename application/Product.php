<?php
class Product
{
    protected $name;

    function __construct($params)
    {
        $this->name = $params['name'];
    }

    function name()
    {
        return $this->name;
    }
}