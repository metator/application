<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

return array(
    'router' => array(
        'routes' => array(

            'order_admin_list' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/admin/order',
                    'defaults' => array(
                        'controller' => 'Order\Controller\Order',
                        'action'     => 'index',
                    ),
                ),
            ),

            'order_admin_view' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/admin/order/:id',
                    'defaults' => array(
                        'controller' => 'Order\Controller\Order',
                        'action'     => 'view',
                    ),
                ),
            ),

        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'Order\Controller\Order' => 'Order\Controller\OrderController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
