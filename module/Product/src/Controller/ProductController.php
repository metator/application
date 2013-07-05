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
        $form = new Form;

        if($this->params('id')) {
            $product = $this->productMapper()->load($this->params('id'));
            $form->populate(array(
                'name'=>$product->getName(),
                'sku'=>$product->getSku(),
                'basePrice'=>$product->getBasePrice(),
            ));
        }

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {
            $product = new Product(array('id'=>$this->params('id')) + $form->getValues());
            $this->productMapper()->save($product);
            return $this->redirect()->toRoute('product_manage');
        }

        return array(
            'form'=>$form
        );
    }

    /** @return \Product\DataMapper */
    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Application\Product\DataMapper');
        }
        return $this->productMapper;
    }
}