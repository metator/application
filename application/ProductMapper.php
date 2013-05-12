<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductMapper
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    /** @param Product */
    function save($product)
    {
        $this->db->insert('product',array(
            'name'=>$product->name(),
            'attributes'=>$this->serializeAttributes($product->attributes())
        ));
        $product_id = $this->db->lastInsertId();
        $this->associateAttributeToProduct($product_id, $product->attributes());
        //$this->savePriceModifiers($product_id, $product);
        return $product_id;
    }

    function serializeAttributes($attributes)
    {
        $attributesArray = array();
        foreach($attributes as $attribute) {
            $attributesArray[$attribute->id()] = $attribute->options();
        }
        return Zend_Json::encode($attributesArray);
    }

    function associateAttributeToProduct($product_id, $attributes)
    {
        foreach($attributes as $attribute) {
            $this->db->insert('product_attribute',array(
                'product_id'=>$product_id,
                'attribute_id'=>$attribute->id()
            ));
        }
    }

    /** @param Product $product */
    function savePriceModifiers($product_id, $product)
    {
        foreach($product->attributes() as $attribute) {
            foreach($attribute->options() as $option) {
                $n = $attribute->name();
                $this->db->insert('product_attribute_pricemodifiers',array(
                    'product_id'=>$product_id,
                    'attribute_id'=>$attribute->id(),
                    'attribute_option_id'=>$attribute->optionId($option),
                    'flat_fee'=>$product->priceModifierFor($attribute->name(), $option)->flatFee()
                ));
            }
        }
    }

    function load($product_id)
    {
        $select = $this->db->select()
            ->from('product')
            ->where('id=?',$product_id)
            ->limit(1);
        $data = $select->query()->fetch();
        $product = new Product($data);
        $this->loadAttributes($product);
        //$this->loadPriceModifiers($product);
        return $product;
    }

    function loadAttributes($product)
    {
        $select = $this->db->select()
            ->from('product_attribute',array('attribute_id'))
            ->where('product_id=?',$product->id());
        $result = $select->query();
        while($attributeID = $result->fetchColumn()) {
            $attribute = $this->loadAttribute($attributeID);
            $product->addAttribute($attribute);
        }
    }

    function loadPriceModifiers($product)
    {
        foreach($product->attributes() as $attribute) {
            foreach($attribute->options() as $option) {
                $select = $this->db->select()
                    ->from('product_attribute_pricemodifiers',array('flat_fee','percentage'))
                    ->where('attribute_option_id=?',$attribute->optionId($option));
                echo $select;

            }
        }
    }

    function loadAttribute($attributeID)
    {
        $attribute_mapper = new AttributeMapper($this->db);
        return $attribute_mapper->load($attributeID);
    }
}