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
use Zend\Paginator\Adapter\DbSelect;
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

    /** @param Product */
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
        $this->associateAttributeToProduct($product_id, $product->attributes());
        $this->savePriceModifiers($product_id, $product);
        $this->saveCategories($product_id, $product->getCategories());
        $this->saveImageHashes($product_id, $product);
        return $product_id;
    }

    function serializeAttributes($attributes)
    {
        $attributesArray = array();
        foreach($attributes as $attribute) {
            $attributesArray[$attribute->id()] = $attribute->options();
        }
        return Json::encode($attributesArray);
    }

    function associateAttributeToProduct($product_id, $attributes)
    {
        foreach($attributes as $attribute) {
            $this->productAttributeTable->insert(array(
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
                $this->priceModifierTable->insert(array(
                    'product_id'=>$product_id,
                    'attribute_id'=>$attribute->id(),
                    'attribute_option_id'=>$attribute->optionId($option),
                    'flat_fee'=>$product->hasPriceModifier($attribute->name(),$option) ?
                        $product->priceModifierFor($attribute->name(), $option)->flatFee() : 0,
                    'percentage'=>$product->hasPriceModifier($attribute->name(),$option) ?
                        $product->priceModifierFor($attribute->name(), $option)->percentage() : 0,
                ));
            }
        }
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
        $rowset = $this->productTable->select(function (Select $select) use ($params,$limit,$offset) {
            $select->where($params);
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

    function findPaginated()
    {
        $select = new Select('product');

        $resultSetPrototype = new ResultSet();
        //$resultSetPrototype->setArrayObjectPrototype(new Album());

        $paginatorAdapter = new DbSelect(
            $select,
            $this->productTable->getAdapter(),
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    function findByCategory($id)
    {
        $id = (int)$id;
        $rowset = $this->productTable->select("`active` = 1 && `id` IN (SELECT `product_id` FROM `product_categories` WHERE `category_id` = $id)");
        $products = array();
        while($row = $rowset->current()) {
            $products[] = $this->doLoad($row);
        }
        return $products;
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
        $this->loadAttributes($product);
        $this->loadCategories($product);
        $this->loadImages($product);
        $this->loadPriceModifiers($product);
        return $product;
    }

    function loadAttributes($product)
    {
        $rowset = $select = $this->productAttributeTable->select(array(
            'product_id'=>$product->id()
        ));
        while($row = $rowset->current()) {
            $attribute = $this->loadAttribute($row['attribute_id']);
            $product->addAttribute($attribute);
        }
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

    function loadPriceModifiers($product)
    {
        foreach($product->attributes() as $attribute) {
            foreach($attribute->options() as $option) {
                $rowset = $select = $this->priceModifierTable->select(array(
                    'attribute_option_id'=>$attribute->optionId($option)
                ));
                $row = $rowset->current();

                $product->addPriceModifiersForOption($attribute->name(), $option, array(
                    'flat_fee'=>$row['flat_fee'],
                    'percentage'=>$row['percentage']
                ));

            }
        }
    }

    function loadAttribute($attributeID)
    {
        $attribute_mapper = new AttributeMapper($this->db);
        return $attribute_mapper->load($attributeID);
    }
}