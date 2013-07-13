<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
