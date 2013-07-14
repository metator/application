<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Application;

use Zend\Mvc\Controller\AbstractActionController as ZendController;
use Zend\Session\Container;
use Metator\Cart\Cart;
use Metator\Cart\CheckoutForm;

class AbstractActionController extends ZendController
{
    protected $productMapper, $attributeMapper;

    /** @return \Metator\Product\DataMapper */
    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Product\DataMapper');
        }
        return $this->productMapper;
    }

    /** @return \Metator\Product\Attribute\DataMapper */
    function attributeMapper()
    {
        if (!$this->attributeMapper) {
            $sm = $this->getServiceLocator();
            $this->attributeMapper = $sm->get('Product\Attribute\DataMapper');
        }
        return $this->attributeMapper;
    }

    /** @return \Metator\Order\DataMapper */
    function orderMapper()
    {
        if (!$this->orderMapper) {
            $sm = $this->getServiceLocator();
            $this->orderMapper = $sm->get('Order\DataMapper');
        }
        return $this->orderMapper;
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

    function cart()
    {
        $session = new Container('metator');
        if(!$session->cart) {
            $session->cart = new Cart;
        }
        return $session->cart;
    }
}