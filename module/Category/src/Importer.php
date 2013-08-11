<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Category;

class Importer
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function importFromText($csvText)
    {
        $this->prepareTempFile($csvText);

        /** Load the categories file */
        $this->query("LOAD DATA INFILE '".$this->categoriesFile."' INTO TABLE `category_import`
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            (id,path,name) ");

        /** Insert *new* categories */
        $this->query("INSERT INTO `category` (`name`) SELECT DISTINCT(`name`) FROM `category_import` i WHERE i.name != '' && i.id=0");

        $this->query("truncate `category_import`");
    }

    /** Remove the header, and write the input with predefined column order. */
    function prepareTempFile($csvText)
    {
        $this->inputFile = sys_get_temp_dir().'/'.uniqid();
        $inputHandle = fopen($this->inputFile,'w');
        fwrite($inputHandle,$csvText);
        fclose($inputHandle);

        $this->categoriesFile = sys_get_temp_dir().'/'.uniqid();
        $this->categoriesHandle = fopen($this->categoriesFile,'w');

        $inputReader = new \Csv_Reader($this->inputFile, new \Csv_Dialect());
        $i = 0;
        while($row = $inputReader->getAssociativeRow()) {
            $i++;
            // skip the header
            if($i==1) {
                continue;
            }

            fputcsv($this->categoriesHandle, array(
                'id' => isset($row['id']) ? $row['id'] : 0,
                'path'=>isset($row['path']) ? $row['path'] : '',
                'name'=>$row['name'],
            ));
        }
    }

    function query($sql)
    {
        return $this->db->query($sql, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    }
}