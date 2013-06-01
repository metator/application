<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryMapperTest extends PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->db = Zend_Registry::get('db');
        $this->db->beginTransaction();
    }

    function tearDown()
    {
        $this->db->rollback();
    }

    function testShouldSaveName()
    {
        $categoryMapper = new CategoryMapper($this->db);
        $id = $categoryMapper->save(array(
            'name'=>'foo'
        ));
        $category = $categoryMapper->load($id);
        $this->assertEquals('foo',$category['name'],'should save a category name');
    }

    function testShouldUpdateName()
    {
        $categoryMapper = new CategoryMapper($this->db);
        $id = $categoryMapper->save(array(
            'name'=>'foo'
        ));
        $categoryMapper->save(array(
            'id'=>$id,
            'name'=>'foobar'
        ));
        $category = $categoryMapper->load($id);
        $this->assertEquals('foobar',$category['name'],'should update a category name');
    }

    function testShouldHaveParents()
    {
        $categoryMapper = new CategoryMapper($this->db);
        $car_id = $categoryMapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $categoryMapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $categoryMapper->save(array(
            'name'=>'wheel',
            'parents'=>array($car_id,$truck_id)
        ));
        $category = $categoryMapper->load($wheel_id);
        $this->assertEquals(array($car_id,$truck_id), $category['parents'], 'should have parents');
    }

    function testShouldUpdateParents()
    {
        $categoryMapper = new CategoryMapper($this->db);
        $car_id = $categoryMapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $categoryMapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $categoryMapper->save(array(
            'name'=>'wheel',
            'parents'=>array($car_id,$truck_id)
        ));
        $categoryMapper->save(array(
            'id'=>$wheel_id,
            'name'=>'wheel',
            'parents'=>array($car_id)
        ));
        $category = $categoryMapper->load($wheel_id);
        $this->assertEquals(array($car_id), $category['parents'], 'should update parents');
    }

    function testShouldRemoveParents()
    {
        $categoryMapper = new CategoryMapper($this->db);
        $truck_id = $categoryMapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $categoryMapper->save(array(
            'name'=>'wheel',
            'parents'=>array($truck_id)
        ));
        $categoryMapper->save(array(
            'id'=>$wheel_id,
            'name'=>'wheel',
            'parents'=>array()
        ));
        $category = $categoryMapper->load($wheel_id);
        $this->assertEquals(array(), $category['parents'], 'should remove parents');
    }
}