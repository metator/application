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
        $inputFile = sys_get_temp_dir().'/'.uniqid();
        $processedFile = sys_get_temp_dir().'/'.uniqid();

        $inputHandle = fopen($inputFile,'w');
        fwrite($inputHandle,$csvText);
        fclose($inputHandle);

        $processedHandle = fopen($processedFile,'w');

        $this->preProcessRows($inputFile, $processedHandle);

        $sql = "LOAD DATA INFILE '".$processedFile."' INTO TABLE `product_import`
        FIELDS TERMINATED BY ','
         OPTIONALLY ENCLOSED BY '\"'

        (sku,name,base_price,attributes,categories) ";

        $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("REPLACE INTO `product` (`sku`,`name`,`base_price`,`attributes`) SELECT `sku`, `name`, `base_price`,`attributes` FROM `product_import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("UPDATE product_import i, product p SET i.product_id = p.id WHERE i.sku = p.sku", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("INSERT INTO product_categories (product_id,category_id) SELECT product_id,categories FROM `product_import` ", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("truncate `product_import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /** Necessary pre-processing to the import rows, like removing the header, exploding multi-valued strings, etc. */
    function preProcessRows($inputFile, $processedHandle)
    {
        $reader = new \Csv_Reader($inputFile, new \Csv_Dialect());
        $i = 0;
        while($row = $reader->getAssociativeRow()) {
            $rows = $this->explodeCategories($row);
            foreach($rows as $row) {
                $i++;
                // skip the header
                if($i==1) {
                    continue;
                }

                fputcsv($processedHandle, $row);
            }
        }
    }

    /**
     * This explodes the rows so MYSQL can select the categoryIDs for a product
     * Example:  a row with 1 category ID stays the same, a row with 3 category IDs becomes 3 rows, etc..
     *          then MYSQL can SELECT categires WHERE product_id = <ID>
     */
    function explodeCategories($row)
    {
        if(isset($row['categories'])) {
            $row['categories'] = explode(';', $row['categories']);
            $rows = array();
            foreach($row['categories'] as $category) {
                unset($row['categories']);
                $rows[] = $row + array('categories'=>$category);
            }
            return $rows;
        } else {
            return array($row);
        }
    }
}