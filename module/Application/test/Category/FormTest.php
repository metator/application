<?php
namespace Application\Category;

use \Application\Category\Form;
use \Application\CategoryMapper;

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

    function testShouldSaveName()
    {
        $mapper = new CategoryMapper($this->db);
        $id = $mapper->save(array(
            'name'=>'foo'
        ));
        $category = $mapper->load($id);
        $this->assertEquals('foo',$category['name'],'should save a category name');
    }

    function testShouldSetValuesTocategory()
    {
        $form = new Form();
        $form->setData([
            'name'=>'wheels',
            'parents'=>array(1,2)
        ]);
        $this->assertTrue($form->isValid());

        $category = $form->getData();
        $this->assertEquals('wheels',$category['name'],'should copy name from form to category');
    }
}