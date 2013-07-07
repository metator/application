<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Metator\Category\Form;

class CategoryController extends AbstractActionController
{
    protected $categoryMapper,$productMapper;

    function viewAction()
    {
        $this->layout('layout/layout-2col-left.phtml');

        $layoutViewModel = $this->layout();

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
        // Use an alternative layout
        $layoutViewModel = $this->layout();

        // add an additional layout to the root view model (layout)
        $sidebar = new ViewModel();
        $sidebar->setTemplate('layout/admin-navigation');
        $layoutViewModel->addChild($sidebar, 'navigation');

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
            $this->productMapper = $sm->get('Application\Product\DataMapper');
        }
        return $this->productMapper;
    }

    /** @return \Application\CategoryMapper */
    function categoryMapper()
    {
        if (!$this->categoryMapper) {
            $sm = $this->getServiceLocator();
            $this->categoryMapper = $sm->get('Application\Category\DataMapper');
        }
        return $this->categoryMapper;
    }
}