<?php
class Attribute
{
    protected $name;
    protected $options;
    protected $price_modifiers=array();

    function __construct($params=array())
    {
        $this->name = isset($params['name']) ? $params['name'] : '';
    }

    function name()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function addOption($option, $params=array())
    {
        $this->options[] = $option;
        if(isset($params['price_modifier'])) {
            $this->price_modifiers[$option] = $params['price_modifier'];
        }
    }

    function options()
    {
        return $this->options;
    }

    function setValue($value)
    {
        if($this->isInvalidValue($value)) {
            throw new Exception('Invalid value for attribute');
        }
        $this->value = $value;
    }

    function isInvalidValue($value)
    {
        return !in_array($value, $this->options);
    }

    function value()
    {
        return $this->value;
    }

    function modifyPrice($price)
    {
        if(!isset($this->price_modifiers[$this->value()])) {
            return $price;
        }
        $price_modifier = $this->price_modifiers[$this->value()];
        return $price_modifier->modify($price);
    }

    function hasPriceModifier()
    {
        return isset($this->price_modifiers[$this->value()]);
    }
}