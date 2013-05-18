<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Product
{
    protected $id;
    protected $name;
    protected $attributes = array();
    protected $attribute_values = array();
    protected $price_modifiers = array();

    function __construct($params=array())
    {
        $this->id = isset($params['id']) ? $params['id'] : '';
        $this->sku = isset($params['sku']) ? $params['sku'] : '';
        $this->name = isset($params['name']) ? $params['name'] : '';
        $this->price = isset($params['price']) ? $params['price'] : '';
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

    function sku()
    {
        return $this->sku;
    }

    function setSku($sku)
    {
        $this->sku = $sku;
    }

    function name()
    {
        return $this->name;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the price for this product as configured. For example if this is a T-Shirt with an attribute called
     * "size" and you have declared "large" T-Shirts cost 10% more, this method will return the base price passed
     * to setPrice() after applying the 10% markup (if the customer configured the product as "large")
     * @return float
     */
    function price()
    {
        $price = $this->price;
        foreach($this->attributes() as $attribute) {
            $attributeName = $attribute->name();
            $value = $this->attributeValue($attributeName);
            $price = $this->modifyPrice($attributeName,$value,$price);
        }
        return $price;
    }

    /**
     * Sets the price for this product, which can be modified if the product is configurable. For example if you
     * add an attribute called "size" to a T-Shirt product, you may declare that 'large' T-Shirts cost 10% more.
     * In that case this is actually the "base" price not the final price used.
     *
     * @param float $price
     */
    function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Add an attribute to this product. Ex "color", attributes have options ex. "red", "blue" which may
     * have price modifiers attached (Ex. red costs $5 extra, blue costs 10% more)
     *
     * $attribute may either be a string or an object of type Attribute. If a string is given,
     * an object of type Attribute will be created with the name given.
     *
     * You may pass an array of $params to configure the attribute with, for example:
     *
     * $product->addAttribute('color', array(
     *    'options'=>array(
     *         'red'=>array('flat_fee'=>5),
     *         'blue'=>array('percentage'=>10)
     *     )
     * ));
     *
     * @param string|Attribute $attribute
     * @param array $params
     * @throws Exception
     */
    function addAttribute($attribute, $params=array())
    {
        if($this->hasAttribute($attribute)) {
            throw new Exception('You may not add an attribute twice');
        }

        // if $attribute is a string, turn it into an object
        if(is_string($attribute)) {
            $attribute = new Attribute(array('name'=>$attribute));
        }

        // if there are no $params['options'] set, just add the Attribute
        if(!is_array($params) || !isset($params['options']) || !is_array($params['options'])) {
            $this->attributes[] = $attribute;
            return;
        }

        //  we need to add options (ex. "red", "blue") and possibly price modifiers
        foreach($params['options'] as $optionKey=>$optionValue) {

            // there are no price modifiers, just add a simple option
            if(is_string($optionValue)) {
                $attribute->addOption($optionValue);
                continue;
            }

            // there are price modifiers, create them & inject them into the product
            $attribute->addOption($optionKey);
            $attributeName = $attribute->name();
            $this->addPriceModifiersForOption($attributeName, $optionKey, $optionValue);
        }

        $this->attributes[] = $attribute;
    }

    function addPriceModifiersForOption($attributeName, $value, $priceModifiers)
    {
        if(isset($priceModifiers['flat_fee'])) {
            $price_modifier = new PriceModifier(array(
                'flat_fee'=>$priceModifiers['flat_fee']
            ));
            $this->price_modifiers[$attributeName][$value] = $price_modifier;
        }

        if(isset($priceModifiers['percentage'])) {
            $price_modifier = new PriceModifier(array(
                'percentage'=>$priceModifiers['percentage']
            ));
            $this->price_modifiers[$attributeName][$value] = $price_modifier;
        }
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

    function modifyPrice($attribute, $selectedOption, $price)
    {
        if($this->attribute($attribute)->isInvalidValue($selectedOption)) {
            throw new Exception('Invalid value for $selectedOption');
        }
        if(!$this->hasPriceModifier($attribute, $selectedOption)) {
            return $price;
        }
        $price_modifier = $this->price_modifiers[$attribute][$selectedOption];
        return $price_modifier->modify($price);
    }

    /**
     * Whether or not this attribute is going to modify the price based on the selected option.
     * @param string $selectedOption the name of the selected option
     * @return bool
     */
    function hasPriceModifier($attribute,$selectedOption)
    {
        return isset($this->price_modifiers[$attribute][$selectedOption]);
    }

    function priceModifierFor($attribute,$value)
    {
        return $this->price_modifiers[$attribute][$value];
    }

    /**
     * Gets the requested attribute by it's name, throws an exception if it doesn't exist. Call hasAttribute()
     * to check if it exists before asking for it.
     *
     * @param $attributeName
     * @return Attribute
     * @throws Exception
     */
    function attribute($attributeName)
    {
        foreach($this->attributes as $attribute) {
            if($attributeName == $attribute->name()) {
                return $attribute;
            }
        }
        throw new Exception("This product does not have the requested attribute [$attributeName]");
    }

    function attributeValue($attribute)
    {
        return $this->attribute_values[$attribute];
    }

    function setAttributeValue($attribute,$value)
    {
        $this->attribute_values[$attribute] = $value;
    }

    /**
     * Get an array of Attribute objects for this product
     * @return array
     */
    function attributes()
    {
        return $this->attributes;
    }
}