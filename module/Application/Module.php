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

        /** add categories to sidebar on every request */
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_RENDER, function() use($e) {

            $sm = $e->getApplication()->getServiceManager();

            /** Only add it if the layout actually has a left column */
            if('layout/layout-2col-left.phtml' == $e->getViewModel()->getTemplate()) {
                /** Grab the Category DataMapper from the SM */
                $categoryMapper = $sm->get('Application\Category\DataMapper');

                /** Render it out to the sidebar in the layout */
                $sidebar = new ViewModel(array(
                    'categories'=> $categoryMapper->findStructuredAll()
                ));
                $sidebar->setTemplate('layout/categories');


                $htmlOutput = $sm->get('viewrenderer')
                    ->render($sidebar);

                $e->getViewModel()->navigation .= $htmlOutput;
                $e->getViewModel()->navigation .= 'I am on the sidebar too!';
            }
        });

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
                'Application\Category\DataMapper' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new \Category\DataMapper($dbAdapter);
                },
            ),
        );
    }
}
