<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cart\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\Session\Container,
    Metator\Cart\Cart;

class CartController extends AbstractActionController
{
    protected $productMapper;

    function indexAction()
    {
        return array(
            'cart'=>$this->cart()
        );
    }

    function addAction()
    {
        $id = $this->params('id');
        $price = $this->productMapper()->load($id)->price();

        $this->cart()->add($id, $price);
        $this->redirect()->toRoute('cart');
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

    function cart()
    {
        $session = new Container('metator');
        if(!$session->cart) {
            $session->cart = new Cart;
        }
        return $session->cart;
    }
}