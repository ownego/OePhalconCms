<?php

namespace OE\Application;

use OE\Object;

class Bootstrap extends Object {
	
	protected $_di;
	 
	protected $_loader; 
	
	protected $_configs;
	
	public function init() {
		$methods = get_class_methods($this);
		foreach ($methods as $method) {
			if(substr($method, 0, 5) === '_init') {
				$this->$method();
			}
		}
	}
	
	/**
	 * Set DI
	 * @param unknown $di
	 */
	public function setDi($di) {
		$this->_di = $di;
	}
	
	/**
	 * Set loader
	 * @param unknown $loader
	 */
	public function setLoader($loader) {
		$this->_loader = $loader;
	}
	
	/**
	 * Set configs
	 * @param unknown $configs
	 */
	public function setConfigs($configs) {
		$this->_configs = $configs;
	}
} 