<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product;

class Importer
{
    protected $db;
    protected $fieldPositions;
    
    protected $intputFile, $productFile, $categoriesFile;
    protected $productHandle, $categoriesHandle;

    function __construct($db)
    {
        $this->db = $db;
    }

    function importFromText($csvText)
    {
        $this->setupFiles($csvText);
        $this->preProcessRows();

        /** Load the products file */
        $this->query("LOAD DATA INFILE '".$this->productFile."' INTO TABLE `product_import`
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            (sku,name,active,base_price,attributes) ");

        /** Load the categories file */
        $this->query("LOAD DATA INFILE '".$this->categoriesFile."' INTO TABLE `product_categories_import`
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            (product_sku,category_id,category_name) ");

        /** Insert the products & update the product IDs in the categories table afterwards */
        $this->query("UPDATE product_import i, product p SET i.product_id = p.id WHERE i.sku = p.sku");
        $this->query("INSERT INTO `product` (`sku`, `active`, `name`, `base_price`, `attributes`) SELECT `sku`, `active`, `name`, `base_price`,`attributes` FROM `product_import` i WHERE i.product_id=0");
        $this->query("UPDATE `product` p, `product_import` i SET p.name = i.name WHERE p.sku = i.sku");

        $this->query("UPDATE product_categories_import i, product p SET i.product_id = p.id WHERE i.product_sku = p.sku");

        /** Insert the new categories & update their category ID after */
        $this->query("UPDATE product_categories_import i, category c SET i.category_id = c.id WHERE i.category_name = c.name");
        $this->query("INSERT INTO `category` (`name`) SELECT DISTINCT(`category_name`) FROM `product_categories_import` i WHERE i.category_name != '' && i.category_id=0");
        
        $this->query("UPDATE product_categories_import i, category c SET i.category_id = c.id WHERE i.category_name = c.name");

        $this->query("INSERT INTO product_categories (product_id,category_id) SELECT product_id,category_id FROM `product_categories_import` ");

        $this->query("truncate `product_import`");
        $this->query("truncate `product_categories_import`");
    }
    
    function setupFiles($csvText)
    {
        /** Split the import file into a product file & categories file */
        $this->inputFile = sys_get_temp_dir().'/'.uniqid();
        $this->productFile = sys_get_temp_dir().'/'.uniqid();
        $this->categoriesFile = sys_get_temp_dir().'/'.uniqid();

        $inputHandle = fopen($this->inputFile,'w');
        fwrite($inputHandle,$csvText);
        fclose($inputHandle);

        $this->productHandle = fopen($this->productFile,'w');
        $this->categoriesHandle = fopen($this->categoriesFile,'w');
    }

    /** Necessary pre-processing to the import rows, like removing the header, exploding multi-valued strings, etc. */
    function preProcessRows()
    {
        $inputReader = new \Csv_Reader($this->inputFile, new \Csv_Dialect());
        $i = 0;
        while($row = $inputReader->getAssociativeRow()) {
            $i++;
            // skip the header
            if($i==1) {
                continue;
            }
            fputcsv($this->productHandle, array(
                'sku'=>$row['sku'],
                'name'=>$row['name'],
                'active'=>isset($row['active']) ? $row['active'] : '',
                'base_price'=>isset($row['base_price']) ? $row['base_price'] : '',
                'attributes'=>isset($row['attributes']) ? $row['attributes'] : '',
                
            ));

            $categories = $this->explodeCategories($row);
            foreach($categories as $category) {
                fputcsv($this->categoriesHandle, array(
                    'product_sku'=>$row['sku'],
                    'category_id'=>$category,
                    'category_name'=>''
                ));
            }

            $categoryNames = $this->explodeCategoryNames($row);
            foreach($categoryNames as $category) {
                fputcsv($this->categoriesHandle, array(
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

    function query($sql)
    {
        return $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }
}