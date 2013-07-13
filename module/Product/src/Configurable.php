<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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