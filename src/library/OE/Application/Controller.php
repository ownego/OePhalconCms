<?php
namespace OE\Application;

use OE\Behaviour\DebugBehaviour;
use OE\Behaviour\TranslationBehaviour;
use App\Helpers\Translate;

class Controller extends \Phalcon\Mvc\Controller {

	use DebugBehaviour,
		TranslationBehaviour;
	
	public $pageTitle;
	
	public function onConstruct() {
		$this->view->setVar('t', new Translate());
		$this->view->setVar('pageTitle', $this->pageTitle);
	}
	
	/**
	 * Redirect to action
	 * 
	 * @param string $action
	 * @param string $controller
	 * @param string $module
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function redirect($action, $controller=null, $module=null) {
		$moduleName = $module ? $module : $this->router->getModuleName();
		$controllerName = $controller ? $controller : $this->dispatcher->getControllerName();
		return $this->response->redirect("/$moduleName/$controllerName/$action");
	}
	
	/**
	 * Refresh action
	 */
	public function refresh() {
		$action = $this->dispatcher->getActionName();
		return $this->gotoAction($action);
	}
}