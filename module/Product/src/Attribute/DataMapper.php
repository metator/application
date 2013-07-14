<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product\Attribute;

use Zend\Db\TableGateway\TableGateway;

class DataMapper
{
    protected $db;
    protected $attributeTable, $attributeValuesTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->attributeTable = new TableGateway('attribute', $this->db);
        $this->attributeValuesTable = new TableGateway('attribute_values', $this->db);
    }

    function index()
    {
        $productDataMapper = new \Metator\Product\DataMapper($this->db);
        $products = $productDataMapper->find();

        $attributes = array();
        $attribute_values = array();

        foreach($products as $product) {
            foreach($product->attributes() as $attribute=>$value) {
                if(!in_array($attribute, $attributes)) {
                    array_push($attributes, $attribute);
                }
                if(!in_array($value, $attribute_values)) {
                    $attribute_values[$attribute] = $value;
                }
            }
        }

        $attribute_ids = array();
        foreach($attributes as $attribute) {
            $this->attributeTable->insert(array(
                'name'=>$attribute
            ));
            $attribute_ids[$attribute] = $this->attributeTable->getLastInsertValue();
        }

        foreach($attribute_values as $attribute=>$value) {
            $this->attributeValuesTable->insert(array(
                'attribute_id'=>$attribute_ids[$attribute],
                'name'=>$value
            ));
        }
    }

    function listAttributes()
    {
        $rowset = $this->attributeTable->select(array());
        $attributes = array();
        foreach($rowset->toArray() as $attribute) {
            array_push($attributes, $attribute['name']);
        }
        return $attributes;
    }

    function listValues($attribute)
    {
        $rowset = $this->attributeTable->select(array(
            'name'=>$attribute
        ));
        $attribute_id = $rowset->toArray()[0]['id'];

        $rowset = $this->attributeValuesTable->select(array(
            'attribute_id'=>$attribute_id
        ));
        $attributes = array();
        foreach($rowset->toArray() as $attribute) {
            array_push($attributes, $attribute['name']);
        }
        return $attributes;
    }
}