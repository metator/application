<?php
class Product
{
    protected $name;
    protected $attributes = array();

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
        $price = $this->price;
        foreach($this->attributes() as $attribute) {
            $price = $attribute->modifyPrice($price);
        }
        return $price;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }

    function addAttribute($attribute)
    {
        $this->attributes[] = $attribute;
    }

    /** @return Attribute */
    function attribute($attributeName)
    {
        foreach($this->attributes as $attribute) {
            if($attributeName == $attribute->name()) {
                return $attribute;
            }
        }
    }

    function attributes()
    {
        return $this->attributes;
    }
}