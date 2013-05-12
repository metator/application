<?php
class Attribute
{
    protected $name;
    protected $options;

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

    function addOption($option)
    {
        $this->options[] = $option;
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
}