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
use Application\Product\Form;

class ProductController extends AbstractActionController
{
    protected $productMapper;

    function manageAction()
    {
        $products = $this->productMapper()->find();
        return new ViewModel(array(
            'products'=>$products
        ));
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
                echo 'its valid';
                print_r($form->getData());
            } else {
                echo 'its not';
                print_r($form->getMessages());
            }
        }

        return new ViewModel(array(
            'form'=>$form
        ));
    }

    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Application\ProductMapper');
        }
        return $this->productMapper;
    }
}