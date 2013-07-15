<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product\Attribute;

use Zend\Db\TableGateway\TableGateway;
use \Zend\Json\Json;
use \Metator\Product\DataMapper as ProductDataMapper;

class DataMapper
{
    protected $db;
    protected $attributeTable, $attributeValuesTable, $productTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->attributeTable = new TableGateway('attribute', $this->db);
        $this->attributeValuesTable = new TableGateway('attribute_values', $this->db);
        $this->productTable = new TableGateway('product', $this->db);
    }

    function index()
    {
        $rowset = $this->db->query("SELECT DISTINCT(attributes) FROM `product`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $all_attributes = array();
        $attribute_values = array();

        while($row = $rowset->current()) {
            $attributes = Json::decode($row['attributes']);

            foreach($attributes as $attribute=>$value) {

                if(!in_array($attribute, $all_attributes)) {
                    array_push($all_attributes, $attribute);
                }
                if(!isset($attribute_values[$attribute])) {
                    $attribute_values[$attribute] = array();
                }
                if(!in_array($value, $attribute_values[$attribute])) {
                    $attribute_values[$attribute][] = $value;
                }
            }
            unset($product);
            echo '.';
        }

        $attribute_ids = array();
        foreach($all_attributes as $attribute) {
            $rowset = $this->attributeTable->select(array(
                'name'=>$attribute
            ));
            if($rowset->count() == 0 ) {
                $this->attributeTable->insert(array(
                    'name'=>$attribute
                ));
                $attribute_ids[$attribute] = $this->attributeTable->getLastInsertValue();
            } else {
                $attribute_ids[$attribute] = $rowset->toArray()[0]['id'];
            }

        }

        foreach($all_attributes as $attribute) {
            foreach($attribute_values[$attribute] as $value) {
                $rowset = $this->attributeValuesTable->select(array(
                    'attribute_id'=>$attribute_ids[$attribute],
                    'name'=>$value
                ));
                if($rowset->count() == 0 ) {
                    $this->attributeValuesTable->insert(array(
                        'attribute_id'=>$attribute_ids[$attribute],
                        'name'=>$value
                    ));
                }
            }
        }
    }

    function listAttributes()
    {
        $rowset = $this->attributeTable->select(array());
        $all_attributes = array();
        foreach($rowset->toArray() as $attribute) {
            array_push($all_attributes, $attribute['name']);
        }
        return $all_attributes;
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
        $all_attributes = array();
        foreach($rowset->toArray() as $attribute) {
            array_push($all_attributes, $attribute['name']);
        }
        return $all_attributes;
    }
}