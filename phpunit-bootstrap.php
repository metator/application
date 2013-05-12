<?php
chdir('../');
require_once 'vendor/autoload.php';
defined('APPLICATION_PATH') || define('APPLICATION_PATH', __DIR__.'/application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');
require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH.'/../config/application.ini');
$application->bootstrap();