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

}