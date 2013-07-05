<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

$dbname = 'metator';
if(getenv('IS_PHPUNIT')) {
    $dbname = 'metator_tests';
}

$config = array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => "mysql:dbname=$dbname;host=localhost",
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'username' => 'root',
        'password' => '',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);

if(file_exists('vendor/bjyoungblood/bjy-profiler')) {
    $dbParams = array(
        'database'  => $dbname,
        'username'  => 'root',
        'password'  => '',
        'hostname'  => 'localhost',
        // buffer_results - only for mysqli buffered queries, skip for others
        'options' => array('buffer_results' => true)
    );

    $config['service_manager']['factories']['Zend\Db\Adapter\Adapter'] = function ($sm) use ($dbParams) {
        $adapter = new BjyProfiler\Db\Adapter\ProfilingAdapter(array(
            'driver'    => 'pdo',
            'dsn'       => 'mysql:dbname='.$dbParams['database'].';host='.$dbParams['hostname'],
            'database'  => $dbParams['database'],
            'username'  => $dbParams['username'],
            'password'  => $dbParams['password'],
            'hostname'  => $dbParams['hostname'],
        ));

        $adapter->setProfiler(new BjyProfiler\Db\Profiler\Profiler);
        if (isset($dbParams['options']) && is_array($dbParams['options'])) {
            $options = $dbParams['options'];
        } else {
            $options = array();
        }
        $adapter->injectProfilingStatementPrototype($options);
        return $adapter;
    };
}
return $config;