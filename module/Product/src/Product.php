<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product;

use \Exception;
use \Application\PriceModifier;

class Product
{
    protected $id;
    protected $name;
    protected $description;
    protected $categories = array();
    protected $attribute_values = array();
    protected $price_modifiers = array();
    protected $image_hashes = array();
    protected $default_image_hash;

    function __construct($params=array())
    {
        $this->id = isset($params['id']) ? $params['id'] : '';
        $this->sku = isset($params['sku']) ? $params['sku'] : '';
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->description = isset($params['description']) ? $params['description'] : '';
        $this->categories = isset($params['categories']) ? $params['categories'] : array();
        $this->attribute_values = isset($params['attributes']) && is_array($params['attributes']) ? $params['attributes'] : array();
        if(isset($params['basePrice'])) {
            $this->price = $params['basePrice'];
        } else {
            $this->price = isset($params['base_price']) ? $params['base_price'] : '';
        }
    }

    function id()
    {
        return $this->id;
    }

    function setId($id)
    {
        if($this->id()>0) {
            throw new Exception('You may not change the ID after it is already set');
        }
        $this->id=$id;
    }

    function getSku()
    {
        return $this->sku;
    }

    function setSku($sku)
    {
        $this->sku = $sku;
    }

    function getName()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function addImageHash($image_hash)
    {
        array_push($this->image_hashes, $image_hash);
    }

    function getImageHashes()
    {
        return $this->image_hashes;
    }

    function getDefaultImageHash()
    {
    return $this->default_image_hash;
    }

    function setDefaultImageHash($image_hash)
    {
        $this->default_image_hash = $image_hash;
    }

    function getBasePrice()
    {
        return $this->price;
    }

    function setBasePrice($price)
    {
        $this->price = $price;
    }

    function attributes()
    {
        return $this->attribute_values;
    }

    function attributeValue($attribute)
    {
        return isset($this->attribute_values[$attribute]) ? $this->attribute_values[$attribute] : null;
    }

    function setAttributeValue($attribute,$value)
    {
        $this->attribute_values[$attribute] = $value;
    }

    function setCategories($categories)
    {
        if(is_null($categories)) {
            $categories = array();
        }
        $this->categories = $categories;
    }

    function getCategories()
    {
        return $this->categories;
    }

}