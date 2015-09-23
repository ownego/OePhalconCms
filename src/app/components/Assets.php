<?php
namespace App\Components;

use Phalcon;
use Phalcon\Mvc\User\Plugin;
use Phalcon\DI;

/**
 * Assets component
 * Integrate assets to the pages
 * @author DucNM 
 *
 */
class Assets extends Plugin {
	
	public function __construct() {
		$this->_init();
	}
	
	/**
	 * Initialize assets
	 */
	protected function _init() {
		// Frontend head script collection
		$this->setCollection('frontendHeadScript', array(
			array(
				'uri' => '/skin/common/libs/font-awesome/css/font-awesome.min.css',
				'type' => 'css'	 
			),
			array(
				'uri' => '/skin/common/libs/bootstrap/dist/css/bootstrap.min.css',
				'type' => 'css' 		
			),
			array(
				'uri' => 'http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,700,200italic,300italic,400italic',
				'type' => 'css'		
			),
			array(
				'uri' => '/skin/frontend/default/css/main.css',
				'type' => 'css'
			),	
			array(
				'uri' => '/skin/frontend/default/css/responsive.css',
				'type' => 'css'
			),
            array(
                'uri' => '/skin/common/libs/adminlte/css/fullcalendar/fullcalendar.css',
                'type' => 'css'
            ),
		));
		
		// Frontend foot script collection
		$this->setCollection('frontendFootScript', array(
			array(
				'uri' => '/skin/common/libs/jquery/dist/jquery.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/bootstrap/dist/js/bootstrap.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/frontend/default/js/main.js',
				'type' => 'js'
			),
            array(
                'uri' => '/skin/common/libs/adminlte/plugins/daterangepicker/daterangepicker.js',
                'type' => 'js'
            ),
            array(
                'uri' => '/skin/common/libs/adminlte/plugins/fullcalendar/fullcalendar.min.js',
                'type' => 'js'
            ),
		));
		
		// Backend head script collection
		$this->setCollection('backendHeadScript', array(
			array(
				'uri' => '/skin/common/libs/font-awesome/css/font-awesome.min.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/bootstrap/dist/css/bootstrap.min.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/jquery-ui/themes/base/all.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/jquery-ui-bootstrap/jquery.ui.theme.css',
				'type' => 'css'
			),
		    
		    // Adminlte
		    array(
				'uri' => 'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/dist/css/AdminLTE.min.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/dist/css/skins/_all-skins.min.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/morris/morris.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/fullcalendar/fullcalendar.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/daterangepicker/daterangepicker-bs3.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/oe/grid/css/style.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/common/oe/form/css/style.css',
				'type' => 'css'
			),
			array(
				'uri' => '/skin/backend/default/css/style.css',
				'type' => 'css'
			),
		));
		
		// Backend head script collection for IE only
		$this->setCollection('backendHeadScriptIE', array(
			array(
				'uri' => 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
				'type' => 'js'
			),
			array(
				'uri' => 'https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js',
				'type' => 'js'
			),
		));
		
		$this->setCollection('backendFootScript', array(
			array(
				'uri' => '/skin/common/libs/jquery/dist/jquery.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/jquery-ui/jquery-ui.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/bootstrap/dist/js/bootstrap.min.js',
				'type' => 'js'
			),
			array(
				'uri' => 'http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/morris/morris.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/sparkline/jquery.sparkline.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
				'type' => 'js'
			),
// 			array(
// 				'uri' => '/skin/common/libs/adminlte/plugins/fullcalendar/fullcalendar.min.js',
// 				'type' => 'js'
// 			),
// 			array(
// 				'uri' => '/skin/common/libs/adminlte/plugins/jqueryKnob/jquery.knob.js',
// 				'type' => 'js'
// 			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/daterangepicker/daterangepicker.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/iCheck/icheck.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/plugins/slimScroll/jquery.slimscroll.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/libs/adminlte/dist/js/app.min.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/oe/grid/js/main.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/common/oe/form/js/main.js',
				'type' => 'js'
			),
			array(
				'uri' => '/skin/backend/default/js/common.js',
				'type' => 'js'
			),
		));
	}
	
	/**
	 * getInstance method
	 * @return Asset instance 
	 */
	public static function getInstance() {
		static $instance = null;
		if(null === $instance) {
			$instance = new static();
		}
		return $instance;
	}
	
	/**
	 * Set asset collection
	 * @param String $name
	 * @param Array $resources
	 * @param String $prefix
	 * @return $this
	 */
	public function setCollection($name, $resources, $prefix = null) {
		$assets = $this->getDI()->get('assets');
		
		foreach ($resources as $resource) {

			// Create collection
			$collection = $assets->collection($name);

			// Set prefix for collection if exist
			if($prefix) {
				$collection->setPrefix($prefix);
			}

			// Add resource with appropriate type
			switch($resource['type']) {
				case 'css':
					$collection->addCss($resource['uri']);
					break;
				case 'js':
					$collection->addJs($resource['uri']);
					break;
				default:
					break;
			}
		}
		
		return $this;
	}
	
	/**
	 * Get asset collection
	 * @param String $name
	 * @return collection
	 */
	public function getCollection($name) {
		return $this->getDI()->get('assets')->collection($name);
	}
	
	/**
	 * Render collection to html
	 * @param String $name
	 * @param String $type
	 * @return Collection in html format
	 */
	public function renderCollection($name, $type) {
		$assets = $this->getDI()->get('assets');
		switch ($type) {
			case 'css':
				return $assets->outputCss($name);
				break;
			case 'js':
				return $assets->outputJs($name);
				break;
			default:
				break;
		}
		return null;
	}
}