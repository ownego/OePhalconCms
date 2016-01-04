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

			//Listen for events produced in the dispatcher using the Security plugin
			$eventsManager->attach('dispatch', new \SecurityPlugin());
			
			// Add listen for events handler request not found
			$eventsManager->attach("dispatch", function($event, $dispatcher, $exception) {
			    if ($event->getType() == 'beforeException') {
			        switch ($exception->getCode()) {
			        	case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
			        	case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
			        	    $dispatcher->forward(array('module' => 'backend', 'controller' => 'error', 'action' => 'show404'));
			        	    return false;
			        }
			    }
			});
		
			//Bind the EventsManager to the Dispatcher
			$dispatcher->setEventsManager($eventsManager);
			
			return $dispatcher;
		});
	}
}
