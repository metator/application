<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Attribute is a class to represent a product's attribute, such as "color" or "size". Attributes have options
 * such as "red","blue","green" or "small","medium","large". These options can have price markups (flat fees & percentage)
 * associated to them based on the value selected.
 */
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
        if(isset($params['flat_fee'])) {
            $price_modifier = new PriceModifier(array(
                'flat_fee'=>$params['flat_fee']
            ));
            $this->price_modifiers[$option] = $price_modifier;
        }
        if(isset($params['percentage'])) {
            $price_modifier = new PriceModifier(array(
                'percentage'=>$params['percentage']
            ));
            $this->price_modifiers[$option] = $price_modifier;
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