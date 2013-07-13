<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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

            'image_resized' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/image/:size/:hash',
                    'defaults' => array(
                        'controller' => 'Image\Controller\Image',
                        'action'     => 'resized',
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
