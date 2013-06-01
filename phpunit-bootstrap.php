<?php
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
class phpunit_bootstrap
{
    static $serviceManager;

    static function go()
    {
        /**
         * This makes our life easier when dealing with paths. Everything is relative
         * to the application root now.
         */
        chdir(__DIR__);

        // Setup autoloading
        require 'init_autoloader.php';

        // Run the application!
        $config = require 'config/application.config.php';
        Zend\Mvc\Application::init($config);

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        self::$serviceManager = $serviceManager;
    }

    static public function getServiceManager()
    {
        return self::$serviceManager;
    }
}

phpunit_bootstrap::go();