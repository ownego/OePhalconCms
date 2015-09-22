<?php

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '',
        'dbname'      => 'anime',
    ),
    'application' => array(
        'controllersDir' => array(
				'backend' => __DIR__ . '../../../modules/backend/controllers/',
                'frontend' => __DIR__ . '../../../modules/frontend/controllers/',
                'cms' => __DIR__ . '../../../modules/cms/controllers/',
		),
            
        'modelsDir' => array(
                'base' => __DIR__ . '../../../models/',
                'backend' => __DIR__ . '../../../modules/backend/models/',
                'frontend' => __DIR__ . '../../../modules/frontend/models/',
                'cms' => __DIR__ . '../../../modules/cms/models/',
        ),
    		
        'gridsDir' => array(
                'backend' => __DIR__ . '../../../modules/backend/grids/',
                'frontend' => __DIR__ . '../../../modules/frontend/grids/',
                'cms' => __DIR__ . '../../../modules/cms/grids/',
        ),
    		
        'detailviewsDir' => array(
                'backend' => __DIR__ . '../../../modules/backend/detailviews/',
                'frontend' => __DIR__ . '../../../modules/frontend/detailviews/',
                'cms' => __DIR__ . '../../../modules/cms/detailviews/',
        ),
    		
        'formsDir' => array(
                'backend' => __DIR__ . '../../../modules/backend/forms/',
                'frontend' => __DIR__ . '../../../modules/frontend/forms/',
                'cms' => __DIR__ . '../../../modules/cms/forms/',
        ),
            
        'viewsDir' => array(
                'backend' => __DIR__ . '../../../modules/backend/views/themes/default/templates/',
                'frontend' => __DIR__ . '../../../modules/frontend/views/themes/default/templates/',
                'cms' => __DIR__ . '../../../modules/cms/views/themes/default/templates/',
        ), 
            
        'pluginsDir'     => __DIR__ . '/../../app/plugins/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'cacheDir'       => __DIR__ . '/../../app/cache/',
        'baseUri'        => '',
    ),
    // key is folder name, value is option string
    'forms' => array('backend'=>'backend', 'no-forms'=>'no forms'),  
));
