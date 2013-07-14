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
    protected $attributeTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->attributeTable = new TableGateway('attribute', $this->db);
    }

    function index()
    {
        $productDataMapper = new \Metator\Product\DataMapper($this->db);
        $products = $productDataMapper->find();
        $attributes = array();
        foreach($products as $product) {
            foreach($product->attributes() as $attribute=>$value) {
                if(!in_array($attribute, $attributes)) {
                    array_push($attributes, $attribute);
                }
            }
        }

        foreach($attributes as $attribute) {
            $this->attributeTable->insert(array(
                'name'=>$attribute
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
}