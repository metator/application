<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\ProductMapper;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\View\Model\ViewModel;

class Module
{
    protected $categoryMapper;

    function init($moduleManager)
    {
        /** Make sure the ZfcUser is loaded previously, so we can override it later. */
        $moduleManager->loadModule('ZfcUser');
    }

    public function onBootstrap(MvcEvent $e)
    {
        /** Hook into ZfcUser & add new registration field(s) */
        $events = $e->getApplication()->getEventManager()->getSharedManager();
        $events->attach('ZfcUser\Form\Register','init', function($e) {
            $form = $e->getTarget();
            $form->add(array(
                'name'=>'Test',
                'options'=>array('label'=>'Test')
            ));
        });

        /** Assign the layout for this route, based on the `route_layouts` key of the config */
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $route = $e->getRouteMatch()->getMatchedRouteName();
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['route_layouts'][$route])) {
                $controller->layout($config['route_layouts'][$route]);
                $controller->layout()->controller = get_class($controller);
            }
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Category\DataMapper' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new \Category\DataMapper($dbAdapter);
                },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables'=>array(
                'categoryStructure'=>'\Application\View\Helper\CategoryStructure',
            )
        );

    }
}
