<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Order\Controller;

use Application\AbstractActionController;
use Zend\Session\Container;
use Metator\Cart\Cart;
use Metator\Cart\CheckoutForm;

class OrderController extends AbstractActionController
{
    protected $orderMapper;

    function indexAction()
    {

    }

    function viewAction()
    {
        $order = $this->orderMapper()->load($this->params('id'));
        return array(
            'order'=>$order
        );
    }

}