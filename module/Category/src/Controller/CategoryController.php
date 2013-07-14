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
    protected $categoryMapper,$productMapper;

    function viewAction()
    {
        $category = $this->categoryMapper()->load($this->params('id'));

        $page = $this->params()->fromQuery('page',1);
        $perpage = 6;
        $offset = ($page * $perpage)-$perpage;

        $products = $this->productMapper()->find(['category'=>$this->params('id')], $offset, $perpage);
        $productCount = $this->productMapper()->count(['category'=>$this->params('id')]);

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
            'category'=>$category['name'],
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

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {
            $this->categoryMapper()->save($form->getValues());
            return $this->redirect()->toRoute('category_manage');
        }

        return array(
            'form'=>$form
        );
    }

}