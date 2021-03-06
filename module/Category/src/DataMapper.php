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

    function listForProductForm()
    {
        $categories = array();
        foreach($this->findAll() as $category) {
            if($category['paths']) {
                $prefix = str_repeat('-', count(explode('/',$category['paths'][0])));
            } else {
                $prefix = '';
            }
            $categories[$category['id']] = $prefix . $category['name'];
        }
        return $categories;
    }

    function listForCategoryForm()
    {
        $categories = array();
        foreach($this->findAll() as $category) {
            if(!$category['paths']) {
                $path = $category['id'];
            } else {
                $path = $category['paths'][0] . '/' . $category['id'];
            }
            $categories[$path] = $category['name'];
        }
        return $categories;
    }

    function findStructuredAll($parent=null)
    {
        $categories = $this->findAll();
        $top_level = array();
        foreach($categories as $category) {
            if(!$parent && !count($category['paths'])) {
                $top_level[] = $category;
            }
        }

        foreach($top_level as $key => $top_category) {
            $top_level[$key]['children'] = $this->findChildren($top_category['id'], true);
        }
        return $top_level;
    }

    function findChildren($path, $recursion=false)
    {
        $adapter = $this->db;

        $sql = new Sql($adapter);
        $select = $sql->select()
            ->from('category')
            ->join('category_structure', 'category.id = category_structure.category_id',array())
            ->where(array('path'=>$path));

        $string = $sql->getSqlStringForSqlObject($select);
        $result = $this->db->query($string, $adapter::QUERY_MODE_EXECUTE);
        $result = (array)$result->toArray();

        foreach($result as $key=>$row) {
            $result[$key]['paths'] = $this->loadParents($row['id']);
        }

        if($recursion) {
            foreach($result as $key=>$category) {
                $result[$key]['children'] = $this->findChildren($path.'/'.$category['id'],true);
            }
        }

        return $result;
    }

    function findAll()
    {
        $rows = $this->categoryTable->select(['active'=>1]);
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
        $category['paths'] = $this->loadParents($id);
        return $category;
    }

    function insert($category)
    {
        $this->categoryTable->insert(array(
            'name'=>$category['name'],
            'active'=>isset($category['active']) ? $category['active'] : 1,
        ));
        $category['id'] = $this->categoryTable->getLastInsertValue();
        $this->insertPaths($category);
        return $category['id'];
    }

    function update($category)
    {
        $this->categoryTable->update(array(
            'name'=>$category['name']
        ),array('id'=>$category['id']));
        $this->categoryStructureTable->delete(array(
            'category_id'=>$category['id']
        ));
        $this->insertPaths($category);
        return $category['id'];
    }

    function insertPaths($category)
    {
        if(!isset($category['paths'])) {
            return;
        }
        foreach($category['paths'] as $parent) {
            $this->categoryStructureTable->insert(array(
                'category_id'=>$category['id'],
                'path'=>$parent
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
            $parents[] = $row['path'];
        }
        return $parents;
    }

    function deactivate($id)
    {
        $this->categoryTable->update(['active'=>0], ['id'=>$id]);
    }
}