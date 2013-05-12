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
        if($this->hasAttribute($attribute)) {
            throw new Exception('You may not add an attribute twice');
        }
        $this->attributes[] = $attribute;
    }

    /**
     * Check if this product has the requested attribute.
     *
     * $attributeToCheckFor may be either a string element type, or an object of type Attribute
     *
     * @param  string|Attribute $attributeToCheckFor
     * @return bool whether this product has the attribute
     */
    function hasAttribute($attributeToCheckFor)
    {
        if(is_string($attributeToCheckFor)) {
            foreach($this->attributes as $attribute) {
                if($attributeToCheckFor == $attribute->name()) {
                    return true;
                }
            }
        } else {
            foreach($this->attributes as $attribute) {
                if($attributeToCheckFor == $attribute) {
                    return true;
                }
            }
        }
        return false;
    }

    /** @return Attribute */
    function attribute($attributeName)
    {
        foreach($this->attributes as $attribute) {
            if($attributeName == $attribute->name()) {
                return $attribute;
            }
        }
        throw new Exception('This product does not have the requested attribute');
    }

    function attributes()
    {
        return $this->attributes;
    }
}