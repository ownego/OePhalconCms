<?php
$config = require APP_PATH . '/config/'. APP_ENV . '/config.php';

$application = array();
$application['modelsDir']['base'] = APP_PATH. '/models/';
$application['modelsNamespace']['base'] = 'App\Models';
$application['pluginsDir'] = APP_PATH .'/plugins/';
$application['libraryDir'] = APP_PATH .'/library/';
$application['cacheDir'] = APP_PATH .'/../var/cache/';
$application['baseUri'] = $config['baseUri'];

foreach($config['modules'] as $moduleName => $class) {
    $varPath = APP_PATH . "/modules/$moduleName/%s/";
    $varNamespace = "App\Modules\\". ucfirst($moduleName) ."\%s";
    
    $application['controllersDir'][$moduleName] = sprintf($varPath, 'controllers');
    $application['modelsDir'][$moduleName] = sprintf($varPath, 'models');
    $application['gridsDir'][$moduleName] = sprintf($varPath, 'grids');
    $application['formsDir'][$moduleName] = sprintf($varPath, 'forms');
    $application['viewsDir'][$moduleName] = sprintf($varPath, 'views/themes/default/templates');
    $application['detailViewsDir'][$moduleName] = sprintf($varPath, 'detailviews');
    
    $application['controllersNamespace'][$moduleName] = sprintf($varNamespace, 'Controllers');
    $application['modelsNamespace'][$moduleName] = sprintf($varNamespace, 'Models');
    $application['formsNamespace'][$moduleName] = sprintf($varNamespace, 'Forms');
    $application['gridsNamespace'][$moduleName] = sprintf($varNamespace, 'Grids');
    $application['detailViewsNamespace'][$moduleName] = sprintf($varNamespace, 'DetailViews');
}

return new \Phalcon\Config(array(
    'database' => $config['db'],
    'application' => $application,
    // key is folder name, value is option string
    'forms' => array('backend'=>'backend', 'no-forms'=>'no forms'),  
));