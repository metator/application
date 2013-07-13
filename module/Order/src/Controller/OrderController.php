<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
        return array(
            'orders' => $this->orderMapper()->findAll()
        );
    }

    function viewAction()
    {
        $order = $this->orderMapper()->load($this->params('id'));
        return array(
            'order'=>$order
        );
    }

}