<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Application;

use Metator\Product\DataMapper as ProductDataMapper,
    Metator\Product\Product;

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

        $csv = new \Csv_Reader($path,new \Csv_Dialect);
        $this->fieldPositions = $csv->getRow();
        while( $row = $csv->getRow()) {
            $this->handleRow($row);
        }
    }

    function handleRow($row)
    {
        $product = new Product(array(
            'sku'=>$row[0],
            'name'=>$row[1]
        ));

        $product_mapper = new ProductDataMapper($this->db);
        $product_mapper->save($product);
    }
}