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

    }

    function load($product_id)
    {
        $select = $this->db->select()
            ->from('product')
            ->where('id=?',$product_id)
            ->limit(1);
        $data = $select->query()->fetch();
        return new Product($data);
    }
}