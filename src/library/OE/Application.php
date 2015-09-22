<?php

/**
 * 
 * @author Trong Hoan
 *
 */

namespace OE;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;

class Application extends \Phalcon\Mvc\Application {
	
	protected $_configPath;
	
	protected $_di;
	
	protected $_loader;
	
	protected $_bootstrap;
	
	protected $_configArray = [
		'db',
		'cache',
		'logger',
		'modules',
		'defaultModule',
		'debug',
		'baseUri', 
		'view', 
		'session', 
		'assets', 
		'metadata', 
		'annotations'
	];
	
	public function __construct($di, $configPath=null) {
		parent::__construct($di);
		
		$this->_setDi($di);
		$this->_setConfigPath($configPath);
		$this->_initLoader();
	}
		
	/**
	 * Deploy application
	 */
	public function run() {
		// Init core directory
		$this->_registerDirs();		
		$this->_registerConfigs();
		$this->_registerServices();
		
		// Bootstrap application
		$bootstrap = new \Bootstrap();
		$bootstrap->setDi($this->_di);
		$bootstrap->setLoader($this->_loader);
		$bootstrap->setConfigs($this->_getConfigs());
		$bootstrap->init();
		
		echo $this->handle()->getContent();	
	}		
	
	/**
	 * Init loader
	 */
	protected function _initLoader() {
		$this->_loader = new \Phalcon\Loader();
	}	
	
	/**
	 * Register configurations
	 */
	protected function _registerConfigs() {
		$this->_configArray = array_flip($this->_configArray);
		$files = scandir($this->_configPath);
		foreach ($files as $file) {
			$file = $this->_configPath . '/' . $file;
			if(is_file($file)) {
				$config = $this->_readConfig($file);
				if( ! empty($config) ) {
					$configKeys = array_keys($config);
					if(count($configKeys) == 1) {
						$this->_addConfig($configKeys[0], $config);
					} else {
						foreach ($configKeys as $ck) {
							$this->_addConfig($ck, $config[$ck]);							
						}
					}
				}
			}
		}
	}
	
	/**
	 * Add config application
	 * 
	 * @param unknown $key
	 * @param unknown $value
	 */
	protected function _addConfig($key, $value) {
		if(isset($this->_configArray[$key])) {
			$this->_configArray[$key] = $value;
		} else {
			if(is_array($value)) {
				$this->_configArray += $value;
			} else {
				$this->_configArray[] = $value;
			}
		}
	}
	
	/**
	 * Read configs file
	 * 
	 * @param unknown $file
	 * @return multitype:
	 */
	protected function _readConfig($file) {
		$fileinfo = pathinfo($file);
		$config   = [];
		
		switch ($fileinfo['extension']) {
			case 'php':
				$config = require_once $file;
				$config = is_array($config) ? $config : [];
			break;
			
			case 'ini':
				$configIni = new \OE\Application\Config\Ini($file);
				$config = $configIni->getConfig();
			break;
				
			case 'json':
				$configJson = new \OE\Application\Config\Json($file);
				$config = $configJson->getConfig();
			break;	
				
			case 'xml':
				$configXml = new \OE\Application\Config\Xml($file);
				$config = $configXml->getConfig();
			break;	
		}
		
		return $config;
	}
		
	/**
	 * Register library dirs
	 */
	protected function _registerDirs() {
		$this->_loader->registerDirs(
			array(
				APP_PATH,
				APP_PATH . '/plugins',
				APP_PATH . '/../library'
			)
		)->register();
	}
		
	/**
	 * Register init service
	 * 
	 * @return void
	 */
	protected function _registerServices() {
		$configs = $this->_getConfigs();
		$di = $this->_di;
		
		$di->set('config', $configs);
		
		// Register database
		$di->set('db', function() use($configs) {
			return new \Phalcon\Db\Adapter\Pdo\Mysql(
				array(
					'host' => $configs->db->host,
					'port' => $configs->db->port,
					'dbname' => $configs->db->dbname,
					'username' => $configs->db->username,
					'password' => $configs->db->password,
				)
			);
		});
		
		$di->setShared('eventsManager', function() {
			$eventsManager = new EventsManager();
			return $eventsManager; 
		});
		
		// Session service
		$di->setShared('session', function() {
			$session = new \Phalcon\Session\Adapter\Files();
			$session->start();
			return $session;
		});
		
		// Url service
		$di->set('url', function() use($configs){
			$url = new \Phalcon\Mvc\Url();
			$url->setBaseUri($configs->baseUri);
			return $url;
		});
		
		// Router service
		$di->set('router', function() use($configs) {
			$router = new \Phalcon\Mvc\Router();
			$router->setDefaultModule($configs->defaultModule);
			$routers = $configs->routers;
			foreach ($routers as $r) {
				$router
				->add($r->pattern, $r->paths->toArray(), $r->httpMethods)
				->setName($r->name);
			}
			return $router;
		});
		
		// Flash message direct
		$flashClass = array(
			'error' => 'alert alert-danger',
			'success' => 'alert alert-success',
			'notice' => 'alert alert-info',
		); 
		
		$di->set('flash', function() use($flashClass) {
			$flash = new \Phalcon\Flash\Direct($flashClass);
			return $flash;
		});
		
		// Flash message session
		$di->set('flashSession', function() use($flashClass) {
			$flashSession = new \Phalcon\Flash\Session($flashClass);
			return $flashSession;
		});
		
		// Security
		$di->set('security', function() {
			$security = new \Phalcon\Security();
			//Set the password hashing factor to 12 rounds
			$security->setWorkFactor(12);
			return $security;
		}, true);
		
		// Assets
		$di->set('assets', function() {
			$assets = new \Phalcon\Assets\Manager();
			return $assets;
		}, true);
		

		// Register modules
		$this->registerModules($configs->modules->toArray());			
	}
	
	protected function _setBootstrap($bootstrap) {
		$this->_bootstrap = $bootstrap;
		return $this;
	}
	
	protected function _setConfigPath($configPath) {
		if(empty($configPath)) {
			$configPath = $this->_getConfigPathDefault();
		}
		$this->_configPath = $configPath;
		return $this;
	}
	
	protected function _setDi($di) {
		$this->_di = $di;
		return $this;
	}
	
	protected function _setLoader($loader) {
		$this->_loader = $loader;
		return $this;
	}
	
	protected function _setConfigs($configs) {
		$this->_config = $configs;
		return $this;
	}
		
	protected function _getDi() {
		return $this->_di;		
	}	
	
	protected function _getConfigPathDefault() {
		return APP_PATH. '/config/'. APP_ENV;
	}
	
	protected function _getConfigs() {
		return new \Phalcon\Config($this->_configArray);
	}
}