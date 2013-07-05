<?php
namespace Metator\Product;

use \Application\Attribute;

class Configurable
{
    protected $products = array();

    function addProduct($product)
    {
        $this->products[] = $product;
    }

    function attribute($attributeName)
    {
        $attribute = new Attribute;
        foreach($this->products as $product) {
            $val = $product->attributeValue($attributeName);
            $attribute->addOption($val);
        }
        return $attribute;
    }

}