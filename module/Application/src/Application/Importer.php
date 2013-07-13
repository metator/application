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

        $sql = "LOAD DATA INFILE '".$path."' INTO TABLE `import` FIELDS TERMINATED BY ','";
        $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("DELETE FROM `import` LIMIT 1", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        $this->db->query("REPLACE INTO `product` (`sku`,`name`,`base_price`,`attributes`) SELECT `sku`, `name`, `base_price`,`attributes` FROM `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        $this->db->query("truncate `import`", \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }
}