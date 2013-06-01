<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
use \Application\CategoryMapper;
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
        $mapper = new CategoryMapper($this->db);
        $id = $mapper->save(array(
            'name'=>'foo'
        ));
        $category = $mapper->load($id);
        $this->assertEquals('foo',$category['name'],'should save a category name');
    }

    function testShouldUpdateName()
    {
        $mapper = new CategoryMapper($this->db);
        $id = $mapper->save(array(
            'name'=>'foo'
        ));
        $mapper->save(array(
            'id'=>$id,
            'name'=>'foobar'
        ));
        $category = $mapper->load($id);
        $this->assertEquals('foobar',$category['name'],'should update a category name');
    }

    function testShouldHaveParents()
    {
        $mapper = new CategoryMapper($this->db);
        $car_id = $mapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'parents'=>array($car_id,$truck_id)
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array($car_id,$truck_id), $category['parents'], 'should have parents');
    }

    function testShouldUpdateParents()
    {
        $mapper = new CategoryMapper($this->db);
        $car_id = $mapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'parents'=>array($car_id,$truck_id)
        ));
        $mapper->save(array(
            'id'=>$wheel_id,
            'name'=>'wheel',
            'parents'=>array($car_id)
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array($car_id), $category['parents'], 'should update parents');
    }

    function testShouldRemoveParents()
    {
        $mapper = new CategoryMapper($this->db);
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'parents'=>array($truck_id)
        ));
        $mapper->save(array(
            'id'=>$wheel_id,
            'name'=>'wheel',
            'parents'=>array()
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array(), $category['parents'], 'should remove parents');
    }
}