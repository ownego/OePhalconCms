<?php
return array(
    'routers' => array(

        //Demo Front End: set default action, default controller
        //http://offshore.local/
        array(
            'name' => 'homePage',
            'pattern' => '/',
            'paths' => array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'index'
            ),
            'httpMethods' => null
        ),

        //Demo : set default action, default controller
        //http://offshore.local/backend/
        array(
            'name' => 'admin-home',
            'pattern' => '/backend',
            'paths' => array(
                'module' => 'backend',
                'controller' => 'auth',
                'action' => 'login'
            ),
            'httpMethods' => null
        ),
        array(
            'name' => 'admin-home',
            'pattern' => '/backend/',
            'paths' => array(
                'module' => 'backend',
                'controller' => 'auth',
                'action' => 'login'
            ),
            'httpMethods' => null
        ),
    		
        array(
            'name' => 'default',
            'pattern' => '/:module/:controller/:action/:params',
            'paths' => array(
                'module' => 1,
                'controller' => 2,
                'action' => 3,
                'params' => 4
            ),
            'httpMethods' => null
        ),

        //Login URL demo : http://offshore.local/login
        array(
            'name' => 'admin-login',
            'pattern' => '/login',
            'paths' => array(
                'module' => 'backend',
                'controller' => 'auth',
                'action' => 'login'
            ),
            'httpMethods' => null
        ),


        //http://offshore.local/backend/route/news/1992/12/09/a
        array(
            'name' => 'login',
            'pattern' => '/news/([0-9]{4})/([0-9]{2})/([0-9]{2})/:params',
            'paths' => array(
                'module' => 'backend',
                'controller' => 'route',
                'action' => 'login',
                'year'       => 1, // ([0-9]{4})
                'month'      => 2, // ([0-9]{2})
                'day'        => 3, // ([0-9]{2})
                'params'     => 4, // :params
            ),
            'httpMethods' => null
        ),

        //http://offshore.local/backend/route/manager
//        array(
//            'name' => 'login',
//            'pattern' => '/:module/route/([a-z]{2})/([a-z\.]+)\.html',
//            'paths' => array(
//                'module' => 'backend',
//                'controller' => 'route',
//                'action' => 'html',
//                'language'   => 1,
//                'file'       => 2
//            ),
//            'httpMethods' => null
//        ),

        //http://offshore.local/backend/route/manager
        array(
            'name' => 'route-index',
            'pattern' => '/route/:action',
            'paths' => array(
                'module' => 'backend',
                'controller' => 'route',
                'action' => 1,
            ),
            'httpMethods' => null
        ),

//        //http://offshore.local/backend/route/edit/12
//        array(
//            'name' => 'login',
//            'pattern' => '/:module/:controller/:action/:id',
//            'paths' => array(
//                'module' => 'backend',
//                "controller" => 1,
//                "action"     => 2,
//                "id"         => 3
//            ),
//            'httpMethods' => null
//        ),
    )


);