<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

return array(
    'router' => array(
        'routes' => array(

            'product' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/product/:id{-}-:name',
                    'defaults' => array(
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'view',
                    ),
                ),
            ),

            'product_new' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/product/new',
                    'defaults' => array(
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'edit',
                    ),
                ),
            ),

            'product_edit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/product/edit/:id',
                    'defaults' => array(
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'edit',
                    ),
                ),
            ),

            'product_deactivate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/product/deactivate/:id',
                    'defaults' => array(
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'deactivate',
                    ),
                ),
            ),

            'product_manage' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/product/manage',
                    'defaults' => array(
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'manage',
                    ),
                ),
            ),

            'product_export' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/product/export',
                    'defaults' => array(
                        'controller' => 'Product\Controller\Product',
                        'action'     => 'export',
                    ),
                ),
            ),

        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Product\Controller\Product' => 'Product\Controller\ProductController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
