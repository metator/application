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
        if(isset($category['id']) && $category['id']) {
            return $this->update($category);
        } else {
            return $this->insert($category);
        }
    }

    function load($id)
    {
        $select = $this->db->select()
            ->from('category')
            ->where('id=?',$id)
            ->limit(1);
        $category = $select->query()->fetch();
        $category['parents'] = $this->loadParents($id);
        return $category;
    }

    function insert($category)
    {
        $this->db->insert('category',array(
            'name'=>$category['name']
        ));
        $category['id'] = $this->db->lastInsertId();
        $this->insertParents($category);
        return $category['id'];
    }

    function update($category)
    {
        $this->db->update('category',array(
            'name'=>$category['name']
        ),$category['id']);
        $this->db->delete('category_structure','category_id='.(int)$category['id']);
        $this->insertParents($category);
        return $category['id'];
    }

    function insertParents($category)
    {
        if(!isset($category['parents'])) {
            return;
        }
        foreach($category['parents'] as $parent) {
            $this->db->insert('category_structure',array(
                'category_id'=>$category['id'],
                'parent_id'=>$parent
            ));
        }
    }

    function loadParents($id)
    {
        $parents = array();
        $select = $this->db->select()
            ->from('category_structure')
            ->where('category_id=?',$id);
        foreach($select->query()->fetchAll() as $row) {
            $parents[] = $row['parent_id'];
        }
        return $parents;
    }
}