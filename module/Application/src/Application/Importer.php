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
        $path = sys_get_temp_dir().'/'.uniqid();
        $h = fopen($path,'w');
        fwrite($h,$csvText);
        fclose($h);

        $reader = new \Csv_Reader($path, new \Csv_Dialect());
        while($row = $reader->getAssociativeRow()) {
            //print_r($row);
        }

        $sql = "LOAD DATA INFILE '".$path."' INTO TABLE `import` FIELDS TERMINATED BY ','  (sku,name,base_price,attributes,categories)";
        $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("DELETE FROM `import` LIMIT 1", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("REPLACE INTO `product` (`sku`,`name`,`base_price`,`attributes`) SELECT `sku`, `name`, `base_price`,`attributes` FROM `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("UPDATE import i, product p SET i.product_id = p.id WHERE i.sku = p.sku", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("INSERT INTO product_categories (product_id,category_id) SELECT product_id,categories FROM `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("truncate `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }
}