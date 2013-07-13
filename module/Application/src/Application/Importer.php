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

        $this->explodeCategories($inputFile, $processedHandle);

        $sql = "LOAD DATA INFILE '".$processedFile."' INTO TABLE `import`
        FIELDS TERMINATED BY ','
         OPTIONALLY ENCLOSED BY '\"'

        (sku,name,base_price,attributes,categories) ";

        $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("DELETE FROM `import` LIMIT 1", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("REPLACE INTO `product` (`sku`,`name`,`base_price`,`attributes`) SELECT `sku`, `name`, `base_price`,`attributes` FROM `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("UPDATE import i, product p SET i.product_id = p.id WHERE i.sku = p.sku", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("INSERT INTO product_categories (product_id,category_id) SELECT product_id,categories FROM `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("truncate `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * This explodes the rows so MYSQL can select the categoryIDs for a product
     * Example:  a row with 1 category ID stays the same, a row with 3 category IDs becomes 3 rows, etc..
     */
    function explodeCategories($inputFile, $processedHandle)
    {
        $reader = new \Csv_Reader($inputFile, new \Csv_Dialect());
        while($row = $reader->getAssociativeRow()) {
            if(isset($row['categories'])) {
                $row['categories'] = explode(';', $row['categories']);
                $rows = array();
                foreach($row['categories'] as $category) {
                    unset($row['categories']);
                    $rows[] = $row + array('categories'=>$category);
                }
                foreach($rows as $row) {
                    fputcsv($processedHandle, $row);
                }
            } else {
                fputcsv($processedHandle, $row);
            }
        }
    }
}