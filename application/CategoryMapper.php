<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryMapper
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    /** @param array associative array describing category to save */
    function save($category)
    {
        if($category['id']) {
            $this->db->update('category',array(
                'name'=>$category['name']
            ),$category['id']);
        } else {
            $this->db->insert('category',array(
                'id'=>$category['id'],
                'name'=>$category['name']
            ));
            $category_id = $this->db->lastInsertId();
            return $category_id;
        }
    }

    function load($id)
    {
        $select = $this->db->select()
            ->from('category')
            ->where('id=?',$id)
            ->limit(1);
        return $select->query()->fetch();
    }
}