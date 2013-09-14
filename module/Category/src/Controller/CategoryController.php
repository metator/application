<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Category\Controller;

use Application\AbstractActionController;
use Zend\View\Model\ViewModel;
use Metator\Category\Form;

class CategoryController extends AbstractActionController
{
    function viewAction()
    {
        if( $this->params()->fromQuery('page') > 100 ) {
            throw new \Exception('You cant go past 100 pages for performance reasons');
        }

        if($this->params('id')) {
            $category = $this->categoryMapper()->load($this->params('id'));
        }

        $page = $this->params()->fromQuery('page',1);
        $perpage = 6;
        $offset = ($page * $perpage)-$perpage;

        $attributes = array();
        $allowed_attributes = $this->attributeMapper()->listAttributes();
        foreach($allowed_attributes as $attribute) {
            if($this->params()->fromQuery($attribute)) {
                $attributes[$attribute] = $this->params()->fromQuery($attribute);
            }
        }

        $criteria = array(
            'attributes'=>$attributes,
            'active'=>1
        );

        if($this->params('id')) {
            $criteria['category'] = $this->params('id');
        }

        $products = $this->productMapper()->find($criteria, $offset, $perpage);
        $productCount = $this->productMapper()->count($criteria);

        $pageAdapter = new \Zend\Paginator\Adapter\Null($productCount);
        $paginator = new \Zend\Paginator\Paginator($pageAdapter);
        $paginator->setItemCountPerPage($perpage);
        $paginator->setCurrentPageNumber($page);

        if($offset+$perpage > $productCount) {
            $end = $productCount;
        } else {
            $end = $offset+$perpage;
        }

        return array(
            'category'=>$this->params('id') ? $category['name'] : '',
            'start'=>$offset+1,
            'end'=>$end,
            'total'=>$productCount,
            'paginator'=>$paginator,
            'products'=>$products,
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

        $id = $this->params()->fromRoute('id');
        if($id) {
            $category = $this->categoryMapper()->load($id);
            $form->getElement('name')->setValue($category['name']);
        }

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {
            $this->categoryMapper()->save($form->getValues());
            return $this->redirect()->toRoute('category_manage');
        }

        return array(
            'form'=>$form
        );
    }

    function deactivateAction()
    {
        $id = $this->params('id');
        $this->categoryMapper()->deactivate($id);
        return $this->redirect()->toRoute('category_manage');
    }

}