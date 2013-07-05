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

class DataMapper
{
    protected $db;
    protected $productTable;
    protected $productAttributeTable;
    protected $productCategoriesAssociationTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->productTable = new TableGateway('product', $this->db);
        $this->productAttributeTable = new TableGateway('product_attribute', $this->db);
        $this->productCategoriesAssociationTable = new TableGateway('product_categories', $this->db);
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
            ), array('id'=>$product->id()));
            $product_id = $product->id();
        } else {
            $this->productTable->insert(array(
                'active'=>1,
                'sku'=>$product->getSku(),
                'name'=>$product->getName(),
                'attributes'=>$this->serializeAttributes($product->attributes()),
                'base_price'=>$product->getBasePrice()
            ));
            $product_id = $this->productTable->getLastInsertValue();
            $product->setId($product_id);
        }
        $this->associateAttributeToProduct($product_id, $product->attributes());
        $this->savePriceModifiers($product_id, $product);
        $this->saveCategories($product_id, $product->getCategories());
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

    function productExists($sku)
    {
        $rowset = $this->productTable->select(array('sku'=>$sku));
        return count($rowset) > 0;
    }

    function find($params=array())
    {
        $params['active']=1;
        $rowset = $this->productTable->select($params);
        $products = array();
        while($row = $rowset->current()) {
            $products[] = $this->doLoad($row);
        }
        return $products;
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