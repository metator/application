<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(

            'cart' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/cart',
                    'defaults' => array(
                        'controller' => 'Cart\Controller\Cart',
                        'action'     => 'index',
                    ),
                ),
            ),

            'cart_add' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/cart/add/:id',
                    'defaults' => array(
                        'controller' => 'Cart\Controller\Cart',
                        'action'     => 'add',
                    ),
                ),
            ),

            'checkout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/checkout',
                    'defaults' => array(
                        'controller' => 'Cart\Controller\Checkout',
                        'action'     => 'index',
                    ),
                ),
            ),

            'checkout_confirmation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/confirmation/:id',
                    'defaults' => array(
                        'controller' => 'Cart\Controller\Checkout',
                        'action'     => 'confirmation',
                    ),
                ),
            ),

        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Cart\Controller\Cart' => 'Cart\Controller\CartController',
            'Cart\Controller\Checkout' => 'Cart\Controller\CheckoutController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
