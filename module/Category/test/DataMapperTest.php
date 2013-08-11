<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Metator\Category;

class DataMapperTest extends \PHPUnit_Framework_TestCase
{

    function setUp()
    {
        $this->db = \phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $this->db->getDriver()->getConnection()->beginTransaction();
    }

    function tearDown()
    {
        $this->db->getDriver()->getConnection()->rollback();
    }

    function testShouldSaveName()
    {
        $mapper = new DataMapper($this->db);
        $id = $mapper->save(array(
            'name'=>'foo'
        ));
        $category = $mapper->load($id);
        $this->assertEquals('foo',$category['name'],'should save a category name');
    }

    function testShouldUpdateName()
    {
        $mapper = new DataMapper($this->db);
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
        $mapper = new DataMapper($this->db);
        $car_id = $mapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'paths'=>array($car_id,$truck_id)
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array($car_id,$truck_id), $category['paths'], 'should have paths');
    }

    function testShouldHaveNestedPath()
    {
        $mapper = new DataMapper($this->db);
        $foo_id = $mapper->save(array(
            'name'=>'foo'
        ));
        $bar_id = $mapper->save(array(
            'name'=>'bar',
            'paths'=>array($foo_id)
        ));
        $baz_id = $mapper->save(array(
            'name'=>'baz',
            'paths'=>array($foo_id.'/'.$bar_id)
        ));
        $category = $mapper->load($baz_id);
        $this->assertEquals(array($foo_id.'/'.$bar_id), $category['paths'], 'should have nested path');
    }

    function testShouldUpdateParents()
    {
        $mapper = new DataMapper($this->db);
        $car_id = $mapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'paths'=>array($car_id,$truck_id)
        ));
        $mapper->save(array(
            'id'=>$wheel_id,
            'name'=>'wheel',
            'paths'=>array($car_id)
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array($car_id), $category['paths'], 'should update paths');
    }

    function testShouldRemoveParents()
    {
        $mapper = new DataMapper($this->db);
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'paths'=>array($truck_id)
        ));
        $mapper->save(array(
            'id'=>$wheel_id,
            'name'=>'wheel',
            'paths'=>array()
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array(), $category['paths'], 'should remove paths');
    }

    function testShouldFindAll()
    {
        $mapper = new DataMapper($this->db);
        $car_id = $mapper->save(array(
            'name'=>'car'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck'
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'paths'=>array($car_id,$truck_id)
        ));
        $categories = $mapper->findAll();

        $expected = array(
            array(
                'id'=>$car_id,
                'name'=>'car',
                'paths'=>array()
            ),
            array(
                'id'=>$truck_id,
                'name'=>'truck',
                'paths'=>array()
            ),
            array(
                'id'=>$wheel_id,
                'name'=>'wheel',
                'paths'=>array($car_id,$truck_id)
            ),

        );

        $this->assertEquals($expected, $categories);
    }

    function testShouldFindChildren()
    {
        $mapper = new DataMapper($this->db);
        $vehicle_id = $mapper->save(array(
            'name'=>'vehicle'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck',
            'paths'=>array($vehicle_id)
        ));

        $categories = $mapper->findChildren($vehicle_id);
        $this->assertEquals(1, count($categories), 'should find only 1 child');
        $this->assertEquals($truck_id, $categories[0]['id'], 'should find "truck" as the 1 child');
    }

    function testShouldFindStructured()
    {
        $mapper = new DataMapper($this->db);
        $foo_id = $mapper->save(array(
            'name'=>'foo'
        ));
        $bar_id = $mapper->save(array(
            'name'=>'bar',
            'paths'=>array($foo_id)
        ));
        $baz_id = $mapper->save(array(
            'name'=>'baz',
            'paths'=>array($foo_id.'/'.$bar_id)
        ));
        $categories = $mapper->findStructuredAll();

        $expected = array(
            array(
                'id'=>$foo_id,
                'name'=>'foo',
                'paths'=>array(),
                'children'=>array(
                    array(
                        'id'=>$bar_id,
                        'name'=>'bar',
                        'paths'=>array($foo_id),
                        'children'=>array(
                            array(
                                'id'=>$baz_id,
                                'name'=>'baz',
                                'paths'=>array($foo_id.'/'.$bar_id),
                                'children'=>array()
                            ),
                        )
                    ),
                )
            )
        );
        $this->assertEquals($expected, $categories);
    }
}