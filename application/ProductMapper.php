<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductMapper
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function save($product)
    {
        $this->db->insert('product',array(
            'name'=>$product->name()
        ));
        return $this->db->lastInsertId();
    }

    function load($product_id)
    {
        $select = $this->db->select()
            ->from('product')
            ->where('id=?',$product_id)
            ->limit(1);
        $data = $select->query()->fetch();
        return new Product($data);
    }
}