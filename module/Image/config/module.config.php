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

            'image' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/image/:hash',
                    'defaults' => array(
                        'controller' => 'Image\Controller\Image',
                        'action'     => 'view',
                    ),
                ),
            ),


        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Image\Controller\Image' => 'Image\Controller\ImageController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'view_helpers'=>array(
        'invokables'=>array(

        )
    )
);
