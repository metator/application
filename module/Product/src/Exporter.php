<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product;

class Exporter
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function exportToText()
    {
        $result = $this->query("select `sku`, `name`, `active` from product order by `sku` ASC");
        $array = $result->toArray();

        $text = "sku,name,active";
        foreach($array as $row) {
            $text .= "\n" . implode(',', $row);
        }
        return $text;
    }

    function query($sql)
    {
        return $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }
}