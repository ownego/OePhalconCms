<?php

$navigationConfig = array(
    'main-menu-backend'=> array(
        array(
            'label' => 'Dashboard',
            'module' => 'backend',
            'controller' => 'index',
            'action'=> 'index',
            'url'   => '',
            'pull-left'=> 'fa fa-dashboard',
            'pull-right'=> '',
        ),
        array(
            'label' => 'Videos',
            'controller' => '',
            'action'=> '',
            'url'   => '',
            'pull-left'=> 'fa fa-indent',
            'pull-right'=> '',
            'pages' => array(
                array(
                    'label' => 'Manage Videos',
                    'module' => 'backend',
                    'controller' => 'video',
                    'action'=> 'index',
                    'url'   => '',
                    'pull-left'=> 'fa fa-angle-double-right',
                    'pull-right'=> '',
                ),
                array(
                    'label' => 'Film Crawler',
                    'module' => 'backend',
                    'controller' => 'crlFilm',
                    'action'=> 'index',
                    'url'   => '',
                    'pull-left'=> 'fa fa-angle-double-right',
                    'pull-right'=> '',
                ),
                array(
                    'label' => 'Eposide Crawler',
                    'module' => 'backend',
                    'controller' => 'crlEposide',
                    'action'=> 'index',
                    'url'   => '',
                    'pull-left'=> 'fa fa-angle-double-right',
                    'pull-right'=> '',
                ),
                array(
                    'label' => 'Video Report',
                    'module' => 'backend',
                    'controller' => 'report',
                    'action'=> 'index',
                    'url'   => '',
                    'pull-left'=> 'fa fa-angle-double-right',
                    'pull-right'=> '',
                ),
            )
        ),
        array(
            'label' => 'Categories',
            'controller' => '',
            'action'=> '',
            'url'   => '',
            'pull-left'=> 'fa fa-users',
            'pull-right'=> '',
            'pages' => array(
                array(
                    'label' => 'Manage Categories',
                    'module' => 'backend',
                    'controller' => 'category',
                    'action'=> 'index',
                    'url'   => '',
                    'pull-left'=> 'fa fa-angle-double-right',
                    'pull-right'=> '',
                ),
                array(
                    'label' => 'Attributes',
                    'module' => 'backend',
                    'controller' => 'attributes',
                    'action'=> 'index',
                    'url'   => '',
                    'pull-left'=> 'fa fa-angle-double-right',
                    'pull-right'=> '',
                ),
            )
        ),
        	
		array(
			'label' => 'Setting',
			'controller' => '',
			'action'=> '',
			'url'   => '',
		   	'pull-left'=> 'fa fa-gears',
		   	'pull-right'=> '',
		   	'pages' => array(
			   	array(
				   	'label' => 'Site Setting',
				   	'module' => 'backend',
				   	'controller' => 'setting',
				    'action'=> 'index',
				    'url'   => '',
				    'pull-left'=> 'fa fa-angle-double-right',
				    'pull-right'=> '',
				),
	   	        array(
   	                'label' => 'Currency',
   	                'module' => 'backend',
   	                'controller' => 'currency',
   	                'action'=> 'index',
   	                'url'   => '',
   	                'pull-left'=> 'fa fa-angle-double-right',
   	                'pull-right'=> '',
	   	        ),
			)
		),
    		
    ),
);