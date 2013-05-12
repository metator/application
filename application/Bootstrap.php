<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    function _initAutoload()
    {
        require_once 'Zend/Loader/Autoloader.php';
        $loader = Zend_Loader_Autoloader::getInstance();
        $loader->setFallbackAutoloader(true);
    }

    function _initDb()
    {
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'=>'localhost',
            'username'=>'root',
            'password'=>'',
            'dbname'=>'metator'
        ));
        Zend_Registry::set('db',$db);
    }
}