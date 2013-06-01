<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Application;
use Zend\Db\TableGateway\TableGateway;
class CategoryMapper
{
    protected $db;
    protected $categoryTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->categoryTable = new TableGateway('category', $this->db);
        $this->categoryStructureTable = new TableGateway('category_structure', $this->db);
    }

    /**
     * Save a category, which is represented by an array. Categories have an id, name & other category's IDs as parents
     * Example:
     * array(
     *  'id' => 3,
     *  'name'=> 'foo'
     *  'parents' => array(1,2)
     * )
     * @param array associative array describing category to save
     * @return integer the category ID
     */
    function save($category)
    {
        if(isset($category['id']) && $category['id']) {
            return $this->update($category);
        } else {
            return $this->insert($category);
        }
    }

    /**
     * Load a category
     * Example:
     * array(
     *  'id' => 3,
     *  'name'=> 'foo'
     *  'parents' => array(1,2)
     * )
     * @param $id integer the category ID to load
     * @return array representing the category
     */
    function load($id)
    {
        $rowset = $this->categoryTable->select(array('id'=>$id));
        $category = $rowset->current();
        $category['parents'] = $this->loadParents($id);
        return $category;
    }

    function insert($category)
    {
        $this->categoryTable->insert(array(
            'name'=>$category['name']
        ));
        $category['id'] = $this->categoryTable->getLastInsertValue();
        $this->insertParents($category);
        return $category['id'];
    }

    function update($category)
    {
        $this->categoryTable->update(array(
            'name'=>$category['name']
        ),$category['id']);
        $this->categoryStructureTable->delete(array(
            'category_id'=>$category['id']
        ));
        $this->insertParents($category);
        return $category['id'];
    }

    function insertParents($category)
    {
        if(!isset($category['parents'])) {
            return;
        }
        foreach($category['parents'] as $parent) {
            $this->categoryStructureTable->insert(array(
                'category_id'=>$category['id'],
                'parent_id'=>$parent
            ));
        }
    }

    function loadParents($id)
    {
        $parents = array();
        $rowset = $this->categoryStructureTable->select(array(
            'category_id'=>$id
        ));
        while($row = $rowset->current()) {
            $parents[] = $row['parent_id'];
        }
        return $parents;
    }
}