<?php
return array(
	'debug' => true,	
	'defaultModule' => 'backend',	
	'baseUri' => '',	
	'db' => array(
		'adapter' => 'Mysql',
		'host' => 'localhost',
		'port' => 3306,
		'username' => 'root',
		'password' => '',
		'dbname' => 'oe_phalcon_cms'
	),
	'modules' => array(
		'backend' => array(
			'className' => 'App\Modules\Backend\Module',
			'path' =>  APP_PATH . '/modules/backend/Module.php',
		),
		'system' => array(
			'className' => 'App\Modules\System\Module',
			'path' =>  APP_PATH . '/modules/system/Module.php',
		)
	),
	'cache' => array (
		'lifetime' => '86400',
		'prefix' => 'oep_',
		'adapter' => 'File',
		'cacheDir' => APP_PATH . '/app/var/cache/data/',
	),
	'logger' => array (
		'enabled' => true,
		'path' => APP_PATH . '/app/var/logs/',
		'format' => '[%date%][%type%] %message%',
	),
	'view' => array (
		'compiledPath' => APP_PATH . '/app/var/cache/view/',
        'compiledExtension' => '.php',
        'compiledSeparator' => '_',
        'compileAlways' => true,
	),
	'session' => array (
		'adapter' => 'Files',
		'uniqueId' => 'OePhalcon_',
	),
	'assets' => array (
		'local' => 'assets/',
		'remote' => false,
	),
	'metadata' => array (
		'adapter' => 'Files',
		'metaDataDir' => APP_PATH . '/app/var/cache/metadata/',
	),
	'annotations' => array (
		'adapter' => 'Files',
		'annotationsDir' => APP_PATH . '/app/var/cache/annotations/',
	),
    'security' => array (
        'aclFile' => APP_PATH . "/../var/security/acl.data",
    ),
); 