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
        $this->saveAttributes($product_id, $product->attributes());
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

    function saveAttributes($product_id, $attributes)
    {
        foreach($attributes as $attribute) {
            $this->db->insert('product_attribute',array(
                'product_id'=>$product_id,
                'attribute_id'=>$attribute->id()
            ));
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

    function loadAttribute($attributeID)
    {
        $attribute_mapper = new AttributeMapper($this->db);
        return $attribute_mapper->load($attributeID);
    }
}