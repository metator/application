<?php
try
{
    chdir('../');
    require_once 'vendor/autoload.php';
    defined('APPLICATION_PATH') || define('APPLICATION_PATH', 'application');
    defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');
    require_once 'Zend/Application.php';
    $application = new Zend_Application(APPLICATION_ENV, 'config/application.ini');
    $application->bootstrap()->run();
}
catch (Exception $e)
{
    $message = 'Unexpected exception of type [' . get_class($e) .
                '] with message ['. $e->getMessage() .
                '] in ['. $e->getFile() .
                ' line ' . $e->getLine() . ']';
    echo '<html><body><center>'  . $message;
    /*
    if (defined('APPLICATION_ENVIRONMENT') && APPLICATION_ENVIRONMENT != 'production'  )
    {*/
        echo '<br /><br />' . $e->getMessage() . '<br />'  . '<div align="left">Stack Trace:' . '<pre>' . $e->getTraceAsString() . '</pre></div>';
    //}
    echo '</body></html>';
    exit(1);
}

