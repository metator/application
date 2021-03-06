<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Metator\Category;

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

    function testShouldSetNameToCategory()
    {
        $form = new Form(null);

        $this->assertTrue($form->isValid([
            'name'=>'wheels'
        ]));

        $category = $form->getValues();
        $this->assertEquals('wheels',$category['name'],'should copy name from form to category');
    }

    function testShouldListPossibleParents()
    {
        $mapper = new DataMapper($this->db);
        $parent1_id = $mapper->save(array(
            'name'=>'Parent 1'
        ));

        $mapper = new DataMapper($this->db);
        $parent2_id = $mapper->save(array(
            'name'=>'Parent 1a',
            'paths'=>array($parent1_id)
        ));

        $form = new Form($mapper);

        $expected = array(
            $parent1_id=>'Parent 1',
            $parent1_id.'/'.$parent2_id=>'Parent 1a',
        );
        $this->assertEquals($expected, $form->getElement('paths')->getMultiOptions());
    }
}