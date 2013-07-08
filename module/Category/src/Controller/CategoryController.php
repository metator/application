<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Metator\Category\Form;

class CategoryController extends AbstractActionController
{
    protected $categoryMapper,$productMapper;

    function viewAction()
    {
        $category = $this->categoryMapper()->load($this->params('id'));
        $products = $this->productMapper()->findByCategory($this->params('id'));
        return array(
            'category'=>$category['name'],
            'products'=>$products
        );
    }

    function manageAction()
    {
        $categories = $this->categoryMapper()->findAll();
        return array(
            'categories'=>$categories
        );
    }

    function editAction()
    {
        $form = new Form($this->categoryMapper());

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {
            $this->categoryMapper()->save($form->getValues());
            return $this->redirect()->toRoute('category_manage');
        }

        return array(
            'form'=>$form
        );
    }

    /** @return \Metator\Product\DataMapper */
    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Product\DataMapper');
        }
        return $this->productMapper;
    }

    /** @return \Application\CategoryMapper */
    function categoryMapper()
    {
        if (!$this->categoryMapper) {
            $sm = $this->getServiceLocator();
            $this->categoryMapper = $sm->get('Category\DataMapper');
        }
        return $this->categoryMapper;
    }
}