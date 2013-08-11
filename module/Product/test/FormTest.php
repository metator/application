<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Metator\Product;

class FormTest extends \PHPUnit_Framework_TestCase
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

    function testShouldListPossibleCategories()
    {
        $mapper = new \Metator\Category\DataMapper($this->db);
        $parent1_id = $mapper->save(array(
            'name'=>'Parent 1'
        ));

        $mapper = new \Metator\Category\DataMapper($this->db);
        $parent2_id = $mapper->save(array(
            'name'=>'Parent 2',
            'paths'=>array($parent1_id)
        ));

        $mapper = new \Metator\Category\DataMapper($this->db);
        $parent3_id = $mapper->save(array(
            'name'=>'Parent 3',
            'paths'=>array($parent1_id.'/'.$parent2_id)
        ));

        $form = new Form($mapper);

        $expected = array(
            $parent1_id=>'Parent 1',
            $parent2_id=>'-Parent 2',
            $parent3_id=>'--Parent 3',
        );
        $this->assertEquals($expected, $form->getElement('categories')->getMultiOptions());
    }
}