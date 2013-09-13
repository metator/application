<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

return array(
    'router' => array(
        'routes' => array(

            'category' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/category/:id{-}-:name',
                    'constraints' => array(
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action'     => 'view',
                    ),
                ),
            ),

            'category_manage' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/category/manage',
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action'     => 'manage',
                    ),
                ),
            ),

            'category_new' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/category/new',
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action'     => 'edit',
                    ),
                ),
            ),

            'category_edit' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/category/edit/:id',
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action'     => 'edit',
                    ),
                ),
            ),

            'category_deactivate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/category/deactivate/:id',
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action'     => 'deactivate',
                    ),
                ),
            ),

        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Category\Controller\Category' => 'Category\Controller\CategoryController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
