<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Product\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Product\Form;
use Product\Product;

class ProductController extends AbstractActionController
{
    protected $productMapper;

    function manageAction()
    {
        $products = $this->productMapper()->find();
        return array(
            'products'=>$products
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
                $product = new Product($form->getData());
                $this->productMapper()->save($product);
                return $this->redirect()->toRoute('product_manage');
            }
        }

        return array(
            'form'=>$form
        );
    }

    /** @return \Application\ProductMapper */
    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Application\Product\DataMapper');
        }
        return $this->productMapper;
    }
}