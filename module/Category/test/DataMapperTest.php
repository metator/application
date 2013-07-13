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
            'parents'=>array($car_id,$truck_id)
        ));
        $category = $mapper->load($wheel_id);
        $this->assertEquals(array($car_id,$truck_id), $category['parents'], 'should have parents');
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
        $mapper = new DataMapper($this->db);
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
            'parents'=>array($car_id,$truck_id)
        ));
        $categories = $mapper->findAll();

        $expected = array(
            array(
                'id'=>$car_id,
                'name'=>'car',
                'parents'=>array()
            ),
            array(
                'id'=>$truck_id,
                'name'=>'truck',
                'parents'=>array()
            ),
            array(
                'id'=>$wheel_id,
                'name'=>'wheel',
                'parents'=>array($car_id,$truck_id)
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
            'parents'=>array($vehicle_id)
        ));

        $categories = $mapper->findChildren($vehicle_id);
        $this->assertEquals(1, count($categories), 'should find only 1 child');
        $this->assertEquals($truck_id, $categories[0]['id'], 'should find "truck" as the 1 child');
    }

    function testShouldFindStructured()
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
            'parents'=>array($car_id,$truck_id)
        ));
        $categories = $mapper->findStructuredAll();

        $expected = array(
            array(
                'id'=>$car_id,
                'name'=>'car',
                'parents'=>array(),
                'children'=>array(
                    array(
                        'id'=>$wheel_id,
                        'name'=>'wheel',
                        'parents'=>array($car_id,$truck_id),
                        'children'=>array()
                    ),
                )
            ),
            array(
                'id'=>$truck_id,
                'name'=>'truck',
                'parents'=>array(),
                'children'=>array(
                    array(
                        'id'=>$wheel_id,
                        'name'=>'wheel',
                        'parents'=>array($car_id,$truck_id),
                        'children'=>array()
                    ),
                )
            ),

        );

        $this->assertEquals($expected, $categories);
    }

    function testShouldFindStructured_2Levels()
    {
        $mapper = new DataMapper($this->db);
        $vehicle_id = $mapper->save(array(
            'name'=>'vehicle'
        ));
        $truck_id = $mapper->save(array(
            'name'=>'truck',
            'parents'=>array($vehicle_id)
        ));
        $wheel_id = $mapper->save(array(
            'name'=>'wheel',
            'parents'=>array($truck_id)
        ));
        $categories = $mapper->findStructuredAll();

        $expected = array(
            array(
                'id'=>$vehicle_id,
                'name'=>'vehicle',
                'parents'=>array(),
                'children'=>array(
                    array(
                        'id'=>$truck_id,
                        'name'=>'truck',
                        'parents'=>array($vehicle_id),
                        'children'=>array(
                            array(
                                'id'=>$wheel_id,
                                'name'=>'wheel',
                                'parents'=>array($truck_id),
                                'children'=>array()
                            ),
                        )
                    )
                )
            ),
        );

        $this->assertEquals($expected, $categories);
    }
}