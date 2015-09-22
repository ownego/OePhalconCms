<?php

namespace App\Modules\Backend;

use App\Plugins\Security;
class Module extends \OE\Application\Module {
	
	public function __construct() {
		parent::__construct(__NAMESPACE__, __DIR__);
	}
	
	public function init() {
		//$this->setTheme('custom');
		//$this->setLayout('main');
		//$this->setViewEngine('volt');
	}
	
	public function registerServices($di) {
		parent::registerServices($di);
		
		$di->set('dispatcher', function() {
		
			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$eventsManager = new \Phalcon\Events\Manager();
			
			$dispatcher->setDefaultNamespace($this->getNamespace(). "\Controllers\\");
			
			//Instantiate the Security plugin
			//$security = new \SecurityPlugin();

			//Listen for events produced in the dispatcher using the Security plugin
			//$eventsManager->attach('dispatch', $security);
		
			//Bind the EventsManager to the Dispatcher
			$dispatcher->setEventsManager($eventsManager);
			
			return $dispatcher;
		});
	}
}