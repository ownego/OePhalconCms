<?php

namespace OE\Application;

use OE\Object;

class Module extends Object {
	
	protected $_namespace;
	protected $_moduleDir;
	protected $_layout = 'main';
	protected $_layoutDir = '../layouts/';
	protected $_theme = 'default';
	protected $_viewEngine = 'phtml';
	protected $_viewDir;
	protected $_themeDir;
	
	/**
	 * Init params
	 * @param unknown $namespace
	 * @param unknown $dir
	 */
	public function __construct($namespace, $moduleDir) {
		$this->_namespace = $namespace;
		$this->_moduleDir = $moduleDir;
		$this->init();
	}
	
	public function init() {}
	
	/**
	 * Auto load default directories
	 */
	public function registerAutoloaders() {
		$namespace = $this->getNamespace();
		$moduleDir = $this->getModuleDir();
		
		$loader = new \Phalcon\Loader();
		$loader->registerNamespaces(array(
			$namespace. '\Controllers' => $moduleDir. '/controllers',	
			$namespace. '\Models' => $moduleDir. '/models',	
			$namespace. '\Grids' => $moduleDir. '/grids',		
			$namespace. '\Grids\Elements' => $moduleDir. '/grids/elements',		
			$namespace. '\DetailViews' => $moduleDir. '/detailviews',
			$namespace. '\Forms' => $moduleDir. '/forms',	
			$namespace. '\Forms\Groups' => $moduleDir. '/forms/groups',
			$namespace. '\Forms\Validations' => $moduleDir. '/forms/validations',
		));
		$loader->register();		
	}
	
	/**
	 * Regiter default services
	 * 
	 * @param unknown $di
	 * @return \Phalcon\Mvc\Dispatcher|\Phalcon\Mvc\View
	 */
	public function registerServices($di) {
		// SET dispatcher
		$di->set('dispatcher', function() {
			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$eventManager = new \Phalcon\Events\Manager();
			$dispatcher->setEventsManager($eventManager); 
			$dispatcher->setDefaultNamespace($this->getNamespace(). "\Controllers\\");
			return $dispatcher;
		});
		
		// SET view and layout
		$di->set('view', function() {
			$view = new View();
			
			$viewDir = $this->getViewDir();
			$layoutDir = $this->getLayoutDir();
			$layout = $this->getLayout(); 
			
			$view->setViewsDir($viewDir);
			$view->setLayoutsDir($layoutDir);
			$view->setLayout($layout);
			
			$view->setVar('viewDir', $viewDir);
			$view->setVar('layoutDir', $layoutDir);
			$view->setVar('layout', $layout);
			
			// View engine
			if($this->getViewEngine() == 'volt') {
				$view->registerEngines(array(
					'.volt' => 'Phalcon\Mvc\View\Engine\Volt'
				));				
			}
			return $view;
		});
	}
	
	/**
	 * Set layout directory
	 * 
	 * @param string $layoutDir
	 * @return \OE\Application\Module
	 */
	public function setLayoutDir($layoutDir) {
		$this->_layoutDir = $layoutDir;
		return $this;
	}
	
	/**
	 * Set layout name
	 * 
	 * @param string $layout
	 * @return \OE\Application\Module
	 */
	public function setLayout($layout) {
		$this->_layout = $layout;
		return $this;
	}
	
	/**
	 * Set theme path
	 * 
	 * @param string $themeDir
	 * @return \OE\Application\Module
	 */
	public function setThemeDir($themeDir) {
		$this->_themeDir = $themeDir;
		return $this;
	}
	
	/**
	 * Set theme name
	 * 
	 * @param string $theme
	 * @return \OE\Application\Module
	 */
	public function setTheme($theme) {
		$this->_theme = $theme;
		return $this;
	}
	
	/**
	 * Set module namespace
	 * 
	 * @param string $namespace
	 * @return \OE\Application\Module
	 */
	public function setNamespace($namespace) {
		$this->_namespace = $namespace;
		return $this;
	}
	
	/**
	 * Set module directory
	 * 
	 * @param string $moduleDir
	 * @return \OE\Application\Module
	 */
	public function setModuleDir($moduleDir) {
		$this->_moduleDir = $moduleDir;
		return $this;
	}
	
	/**
	 * Set view engine
	 * 
	 * @param string $viewEngine
	 * @return \OE\Application\Module
	 */
	public function setViewEngine($viewEngine) {
		$this->_viewEngine = $viewEngine;
		return $this;
	}
	
	/**
	 * Set view directory
	 * 
	 * @param string $viewDir
	 * @return \OE\Application\Module
	 */
	public function setViewDir($viewDir) {
		$this->_viewDir = $viewDir;
		return $this;
	}
	
	/**
	 * Get layout directory
	 * 
	 * @return string
	 */
	public function getLayoutDir() {
		return $this->_layoutDir;
	}
	
	/**
	 * Get layout name
	 * 
	 * @return string
	 */
	public function getLayout() {
		return $this->_layout;
	}
	
	/**
	 * Get theme name
	 * 
	 * @return string
	 */
	public function getTheme() {
		return $this->_theme;
	}
	
	/**
	 * Get namespace name
	 * 
	 * @return string
	 */
	public function getNamespace() {
		return $this->_namespace;
	}
	
	/**
	 * Get module directory
	 * 
	 * @return Ambigous <string, string>
	 */
	public function getModuleDir() {
		return $this->_moduleDir;
	}
	
	/**
	 * Get view engine
	 * 
	 * @return string
	 */
	public function getViewEngine() {
		return $this->_viewEngine;	
	}
	
	/**
	 * Get view directory
	 * 
	 * @return string
	 */
	public function getViewDir() {
		return $this->_viewDir ? $this->_viewDir : ($this->getThemeDir() . '/templates/');
	}
	
	/**
	 * Get theme path
	 * 
	 * @return string
	 */
	public function getThemeDir() {
		return $this->_themeDir ? $this->_themeDir : $this->_getThemeDirDefault(); 
	}
	
	/**
	 * Get default theme path
	 * 
	 * @return string
	 */
	protected function _getThemeDirDefault() {
		return $this->getModuleDir() . '/views/themes/' . $this->getTheme();	
	}	
}