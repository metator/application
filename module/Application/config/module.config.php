<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

$config = array(
    'console' => Array(
        'router' => array(
            'routes' => array(
                'sample_products' => array(
                    'options' => array(
                        'route'    => 'sample products --number= [--categories=]',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action'     => 'sampleproducts',
                        ),
                    ),
                ),

                'index_attributes' => array(
                    'options' => array(
                        'route'    => 'index attributes',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Console',
                            'action'     => 'indexattributes',
                        ),
                    ),
                ),
            ),
        ),
    ),

    'router' => array(
        'routes' => array(

            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Category\Controller\Category',
                        'action'     => 'view',
                    ),
                ),
            ),

        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Attribute' => 'Application\Controller\AttributeController',
            'Application\Controller\Console' => 'Application\Controller\ConsoleController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'route_layouts'=>array(
        'product_manage'=>'layout/admin.phtml',
        'product_edit'=>'layout/admin.phtml',
        'product_new'=>'layout/admin.phtml',
        'category_manage'=>'layout/admin.phtml',
        'category_new'=>'layout/admin.phtml',
        'attribute_manage'=>'layout/admin.phtml',
        'order_admin_list'=>'layout/admin.phtml',
        'order_admin_view'=>'layout/admin.phtml',

        'home'=>'layout/layout-2col-left.phtml',
        'category'=>'layout/layout-2col-left.phtml',
    )
);

foreach(glob('./themes/*') as $theme_directory) {
    $config['view_manager']['template_path_stack'][] = $theme_directory;
}
return $config;