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
    ),

    'view_helpers'=>array(
        'invokables'=>array(
            'productName'=>'\Metator\Product\Name',
            'productURL'=>'\Metator\Product\URL',
        )
    )
);
