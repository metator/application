<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cart\Controller;

use Application\AbstractActionController;
use Zend\Session\Container;
use Metator\Cart\Cart;
use Metator\Cart\CheckoutForm;

class CheckoutController extends AbstractActionController
{
    protected $productMapper, $orderMapper;

    function indexAction()
    {
        $cart = $this->cart();
        $form = new CheckoutForm;

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {
            $order = array(
                'shipping'=>$form->getValues()['shipping'],
                'billing'=>$form->getValues()['billing'],
                'items'=> $this->cart()
            );

            $orderMapper = $this->orderMapper();
            $id = $orderMapper->save($order);
            var_dump($id);

            exit;
        }

        return array(
            'cart'=>$cart,
            'form'=>$form
        );
    }
}