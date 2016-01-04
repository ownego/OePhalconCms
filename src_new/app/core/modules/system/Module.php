<?php

namespace App\Modules\System;

use App\Plugins\Security;
use Phalcon\Loader;

class Module extends \OE\Application\Module {
	
	public function __construct() {
		parent::__construct(__NAMESPACE__, __DIR__);
	}
	
	public function init() {
		$loader = new Loader();
		$loader->registerNamespaces(array(
			'App\Modules\Cms\Models' => APP_PATH . '/modules/cms/models',
			'App\Modules\Backend' => APP_PATH . '/modules/backend',
			'App\Modules\Backend\Controllers' => APP_PATH . '/modules/backend/controllers',
			'App\Modules\Backend\Models' => APP_PATH . '/modules/backend/models',
			'App\Modules\Backend\Forms' => APP_PATH . '/modules/backend/forms',
			'App\Modules\Backend\Forms\Groups' => APP_PATH . '/modules/backend/forms/groups',
			'App\Modules\Backend\DetailViews' => APP_PATH . '/modules/backend/detailviews',
		));
		$loader->register();
		
		// Use backend theme
		$this->setThemeDir(APP_PATH . '/modules/backend/views/themes/default');
		$this->setViewDir(APP_PATH . '/modules/system/views/themes/default/templates');
		$this->setLayoutDir('../../../../../backend/views/themes/default/layouts');
		
		parent::init();
	}
	
	public function registerServices($di) {
		parent::registerServices($di);
		
		$di->set('dispatcher', function() {
		
			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$eventsManager = new \Phalcon\Events\Manager();
			
			$dispatcher->setDefaultNamespace($this->getNamespace(). "\Controllers\\");
			
			//Instantiate the Security plugin
			$security = new \SecurityPlugin();

			//Listen for events produced in the dispatcher using the Security plugin
			$eventsManager->attach('dispatch', $security);
		
			//Bind the EventsManager to the Dispatcher
			$dispatcher->setEventsManager($eventsManager);
			
			return $dispatcher;
		});
	}
}