<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Category;

use Zend\Db\TableGateway\TableGateway,
    Zend\Db\Sql\Sql;

class DataMapper
{
    protected $db;
    protected $categoryTable;

    function __construct($db)
    {
        $this->db = $db;
        $this->categoryTable = new TableGateway('category', $this->db);
        $this->categoryStructureTable = new TableGateway('category_structure', $this->db);
    }

    function listForForm()
    {
        $categories = array();
        foreach($this->findAll() as $category) {
            $categories[$category['id']] = $category['name'];
        }
        return $categories;
    }

    function findStructuredAll($parent=null)
    {
        $categories = $this->findAll();
        $top_level = array();
        foreach($categories as $category) {
            if(!$parent && !count($category['parents'])) {
                $top_level[] = $category;
            }
        }

        foreach($top_level as $key => $top_category) {
            $children = array();
            foreach($categories as $possible_child) {
                if(in_array($top_category['id'], $possible_child['parents'])) {
                    $children[] = $possible_child;
                }
            }
            $top_level[$key]['children'] = $children;
        }

        return $top_level;
    }

    function findChildren($parent_id)
    {
        $adapter = $this->db;

        $sql = new Sql($adapter);
        $select = $sql->select()
            ->from('category')
            ->join('category_structure', 'category.id = category_structure.category_id')
            ->where(array('parent_id'=>$parent_id));

        $string = $sql->getSqlStringForSqlObject($select);
        $result = $this->db->query($string, $adapter::QUERY_MODE_EXECUTE);

        $result = (array)$result->toArray();
        return $result;
    }

    function findAll()
    {
        $rows = $this->categoryTable->select();
        $categories = array();
        while($row = $rows->current()) {
            $categories[] = $this->load($row['id']);
        }
        return $categories;
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
        $category = (array)$rowset->current();
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