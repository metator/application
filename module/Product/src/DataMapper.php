<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product;

use Application\AttributeMapper;
use \Zend\Json\Json;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

class DataMapper
{
    protected $db;
    protected $productTable;
    protected $productAttributeTable;
    protected $productCategoriesAssociationTable;
    protected $productImagesAssociationTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->productTable = new TableGateway('product', $this->db);
        $this->productAttributeTable = new TableGateway('product_attribute', $this->db);
        $this->productCategoriesAssociationTable = new TableGateway('product_categories', $this->db);
        $this->productImagesAssociationTable = new TableGateway('product_images', $this->db);
        $this->priceModifierTable = new TableGateway('product_attribute_pricemodifiers', $this->db);
    }

    function deactivate($id)
    {
        $this->productTable->update(array(
            'active'=>0
        ),array(
            'id'=>$id
        ));
    }

    /** @param \Metator\Product\Product */
    function save($product)
    {
        if($product->id()) {
            $this->productTable->update(array(
                'sku'=>$product->getSku(),
                'name'=>$product->getName(),
                'attributes'=>$this->serializeAttributes($product->attributes()),
                'base_price'=>$product->getBasePrice(),
                'description'=>$product->getDescription(),
            ), array('id'=>$product->id()));
            $product_id = $product->id();
        } else {
            $this->productTable->insert(array(
                'active'=>1,
                'sku'=>$product->getSku(),
                'name'=>$product->getName(),
                'attributes'=>$this->serializeAttributes($product->attributes()),
                'base_price'=>$product->getBasePrice(),
                'description'=>$product->getDescription(),
            ));
            $product_id = $this->productTable->getLastInsertValue();
            $product->setId($product_id);
        }

        $this->saveCategories($product_id, $product->getCategories());
        $this->saveImageHashes($product_id, $product);
        return $product_id;
    }

    function saveCategories($product_id, $categories)
    {
        $this->productCategoriesAssociationTable->delete(array('product_id'=>$product_id));
        foreach($categories as $category) {
            $this->productCategoriesAssociationTable->insert(array(
                'product_id'=>$product_id,
                'category_id'=>$category
            ));
        }
    }

    function saveImageHashes($product_id, $product)
    {
        $image_hashes = $product->getImageHashes();
        $this->productImagesAssociationTable->delete(array('product_id'=>$product_id));
        foreach($image_hashes as $image_hash) {
            $this->productImagesAssociationTable->insert(array(
                'product_id'=>$product_id,
                'image_hash'=>$image_hash,
                'default'=>$product->getDefaultImageHash() == $image_hash
            ));
        }
    }

    function productExists($sku)
    {
        $rowset = $this->productTable->select(array('sku'=>$sku));
        return count($rowset) > 0;
    }

    function find($params=array(), $offset=null, $limit=null)
    {
        if(isset($params['attributes'])) {
            $attributes = $params['attributes'];
            unset($params['attributes']);
        } else {
            $attributes = array();
        }

        if(isset($params['category'])) {
            $category = (int)$params['category'];
            unset($params['category']);
        } else {
            $category = null;
        }

        $rowset = $this->productTable->select(function (Select $select) use ($params,$limit,$offset,$attributes,$category) {
            $select->where($params);
            foreach($attributes as $attribute=>$value) {
                $matchString = sprintf('"%s":"%s"', mysql_real_escape_string($attribute), mysql_real_escape_string($value));
                $select->where("attributes LIKE '%$matchString%'");
            }
            if($category) {
                $select->where("`id` IN (SELECT `product_id` FROM `product_categories` WHERE `category_id` = $category)");
            }
            $this->doSelect($select);
            if($limit || $offset) {
                $select->offset($offset)->limit($limit);
            }
        });
        $products = array();
        while($row = $rowset->current()) {
            $products[] = $this->doLoad($row);
        }
        return $products;
    }

    function doSelect($select)
    {
    }

    function count($params = array())
    {
        if(isset($params['attributes'])) {
            $attributes = $params['attributes'];
            unset($params['attributes']);
        } else {
            $attributes = array();
        }

        if(isset($params['category'])) {
            $category = (int)$params['category'];
            unset($params['category']);
        } else {
            $category = null;
        }

        $matchString = '1';
        foreach($attributes as $attribute=>$value) {
            $pattern = sprintf('"%s":"%s"', mysql_real_escape_string($attribute), mysql_real_escape_string($value));
            $matchString .= " && attributes LIKE '%$pattern%'";
        }

        $sql = "SELECT count(*) FROM `product` WHERE `active` = 1 && $matchString";
        if($category) {
            $sql .= " && `id` IN (SELECT `product_id` FROM `product_categories` WHERE `category_id` = $category)";
        }
        $sql = $this->doCount($sql);
        $result = $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        return current($result->toArray()[0]);
    }

    function doCount($sql)
    {
        return $sql;
    }

    function findBySKu($sku)
    {
        $rowset = $this->productTable->select(array('sku'=>$sku));
        $data = $rowset->current();
        if(!$data) {
            return false;
        }
        return $this->doLoad($data);
    }

    /** @return \Metator\Product\Product */
    function load($product_id)
    {
        $rowset = $this->productTable->select(array(
            'id'=>$product_id
        ));
        $data = $rowset->current();
        return $this->doLoad($data);
    }

    function doLoad($data)
    {
        $product = new Product($data);
        $this->loadCategories($product);
        $this->loadImages($product);
        $this->unserializeAttributes($product, $data['attributes']);
        return $product;
    }

    function loadCategories($product)
    {
        $rowset = $select = $this->productCategoriesAssociationTable->select(array(
            'product_id'=>$product->id()
        ));
        $category_ids = array();
        while($row = $rowset->current()) {
            $category_ids[] = $row['category_id'];
        }
        $product->setCategories($category_ids);
    }

    function loadImages($product)
    {
        $rowset = $select = $this->productImagesAssociationTable->select(array(
            'product_id'=>$product->id()
        ));

        while($row = $rowset->current()) {
            $product->addImageHash($row['image_hash']);
            if($row['default']) {
                $product->setDefaultImageHash($row['image_hash']);
            }
        }
    }

    function loadAttribute($attributeID)
    {
        $attribute_mapper = new AttributeMapper($this->db);
        return $attribute_mapper->load($attributeID);
    }

    function serializeAttributes($attributes)
    {
        return Json::encode($attributes);
    }

    function unserializeAttributes($product, $attributesBlob)
    {
        if(!$attributesBlob) {
            return;
        }
        $attributes = Json::decode($attributesBlob);
        if(is_null($attributes)) {
            return;
        }
        foreach($attributes as $attribute=>$value) {
            $product->setAttributeValue($attribute, $value);
        }
    }
}