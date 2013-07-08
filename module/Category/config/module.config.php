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
