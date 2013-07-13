<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Application;

use Metator\Product\DataMapper as ProductDataMapper;
use Metator\Product\Product;
use Zend\Db\TableGateway\TableGateway;

class Importer
{
    protected $db;
    protected $fieldPositions;

    function __construct($db)
    {
        $this->db = $db;
    }

    function importFromText($csvText)
    {
        /** Split the import file into a product file & categories file */
        $inputFile = sys_get_temp_dir().'/'.uniqid();
        $productImportFile = sys_get_temp_dir().'/'.uniqid();
        $productCategoriesFile = sys_get_temp_dir().'/'.uniqid();

        $inputHandle = fopen($inputFile,'w');
        fwrite($inputHandle,$csvText);
        fclose($inputHandle);

        $productImportHandle = fopen($productImportFile,'w');
        $productCategoriesHandle = fopen($productCategoriesFile,'w');

        $this->preProcessRows($inputFile, $productImportHandle, $productCategoriesHandle);

        /** Load the products file */
        $sql = "LOAD DATA INFILE '".$productImportFile."' INTO TABLE `product_import`
        FIELDS TERMINATED BY ','
         OPTIONALLY ENCLOSED BY '\"'

        (sku,name,base_price,attributes) ";
        $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        /** Load the categories file */
        $sql = "LOAD DATA INFILE '".$productCategoriesFile."' INTO TABLE `product_categories_import`
        FIELDS TERMINATED BY ','
         OPTIONALLY ENCLOSED BY '\"'

        (product_sku,category_id,category_name) ";
        $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        /** Insert the products & update the product IDs in the categories table afterwards */
        $this->db->query("REPLACE INTO `product` (`sku`,`name`,`base_price`,`attributes`) SELECT `sku`, `name`, `base_price`,`attributes` FROM `product_import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("UPDATE product_categories_import i, product p SET i.product_id = p.id WHERE i.product_sku = p.sku", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        /** Insert the new categories & update their category ID after */
        $this->db->query("REPLACE INTO `category` (`name`) SELECT `category_name` FROM `product_categories_import` i WHERE i.category_name != ''", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("UPDATE product_categories_import i, category c SET i.category_id = c.id WHERE i.category_name = c.name", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("INSERT INTO product_categories (product_id,category_id) SELECT product_id,category_id FROM `product_categories_import` ", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("truncate `product_import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /** Necessary pre-processing to the import rows, like removing the header, exploding multi-valued strings, etc. */
    function preProcessRows($inputFile, $productImportHandle, $productCategoriesHandle)
    {
        $inputReader = new \Csv_Reader($inputFile, new \Csv_Dialect());
        $i = 0;
        while($row = $inputReader->getAssociativeRow()) {
            $i++;
            // skip the header
            if($i==1) {
                continue;
            }
            fputcsv($productImportHandle, $row);

            $categories = $this->explodeCategories($row);
            foreach($categories as $category) {
                fputcsv($productCategoriesHandle, array(
                    'product_sku'=>$row['sku'],
                    'category_id'=>$category,
                    'category_name'=>''
                ));
            }

            $categoryNames = $this->explodeCategoryNames($row);
            foreach($categoryNames as $category) {
                fputcsv($productCategoriesHandle, array(
                    'product_sku'=>$row['sku'],
                    'category_id'=>'',
                    'category_name'=>$category
                ));
            }
        }
    }

    /**
     * This explodes the rows so MYSQL can select the categoryIDs for a product
     * Example:  a row with 1 category ID stays the same, a row with 3 category IDs becomes 3 rows, etc..
     *          then MYSQL can SELECT categories WHERE product_id = <ID>
     */
    function explodeCategories($row)
    {
        if(isset($row['categories'])) {
            $categories = array();
            foreach(explode(';', $row['categories']) as $category) {
                if(!is_numeric($category)) {
                    continue;
                }
                array_push($categories, $category);
            }
            return $categories;
        } else {
            return array();
        }
    }

    function explodeCategoryNames($row)
    {
        if(isset($row['categories'])) {
            $categories = array();
            foreach(explode(';', $row['categories']) as $category) {
                if(is_numeric($category)) {
                    continue;
                }
                array_push($categories, $category);
            }
            return $categories;
        } else {
            return array();
        }
    }
}