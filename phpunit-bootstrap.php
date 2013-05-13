<?php
require_once 'vendor/autoload.php';
defined('APPLICATION_PATH') || define('APPLICATION_PATH', 'application');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');
require_once 'Zend/Application.php';
$application = new Zend_Application(APPLICATION_ENV, 'config/application.ini');
$application->bootstrap();