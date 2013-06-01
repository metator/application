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
namespace Application;
class Attribute
{
    protected $id;
    protected $name;
    protected $options = array();
    protected $option_ids = array();

    function __construct($params=array())
    {
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->id = isset($params['id']) ? $params['id'] : '';
        $this->options = isset($params['options']) ? $params['options'] : array();
    }

    function id()
    {
        return $this->id;
    }

    function setId($id)
    {
        if($this->id()>0) {
            throw new \Exception('You may not change the ID after it is already set');
        }
        $this->id=$id;
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
    }

    function options()
    {
        return $this->options;
    }

    function hasOption($option)
    {
        return in_array($option, $this->options);
    }

    function isInvalidValue($value)
    {
        return !in_array($value, $this->options);
    }

    function optionId($option)
    {
        return $this->option_ids[$option];
    }

    function setOptionId($option,$id)
    {
        $this->option_ids[$option] = $id;
    }
}