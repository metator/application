<?php
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
class phpunit_bootstrap
{
    static $serviceManager;

    static function go()
    {
        /** Everything is relative to the application root now. */
        chdir(__DIR__);

        /** Setup autoloading */
        require 'init_autoloader.php';

        /** bootstrap ZF2 */
        $config = require 'config/application.config.php';
        Zend\Mvc\Application::init($config);

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        self::$serviceManager = $serviceManager;

        /** Reset the DB */
        `mysql --user=root -e "drop database IF EXISTS metator_tests"`;
        `mysql --user=root -e "create database metator_tests"`;
        `mysql --user=root metator_tests < install.sql`;

        echo `ls -l`;
    }

    static public function getServiceManager()
    {
        return self::$serviceManager;
    }
}

phpunit_bootstrap::go();