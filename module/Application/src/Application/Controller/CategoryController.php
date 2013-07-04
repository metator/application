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
use Application\Category\Form;

class CategoryController extends AbstractActionController
{
    protected $categoryMapper;

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

        $form = new Form;

        if($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $form->setData($post);
            if($form->isValid()) {
                $this->categoryMapper()->save($form->getData());
                return $this->redirect()->toRoute('category_manage');
            }
        }

        return array(
            'form'=>$form
        );
    }

    /** @return \Application\CategoryMapper */
    function categoryMapper()
    {
        if (!$this->categoryMapper) {
            $sm = $this->getServiceLocator();
            $this->categoryMapper = $sm->get('Application\CategoryMapper');
        }
        return $this->categoryMapper;
    }
}