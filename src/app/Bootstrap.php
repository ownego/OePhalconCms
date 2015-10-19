<?php
class Bootstrap extends \OE\Application\Bootstrap {

	public function _initNamespace() {
		$this->_loader->registerNamespaces(array(
            'App\Models' => APP_PATH . '/models/',
            'App\Grids' => APP_PATH . '/grids/',
            'App\Grids\Elements' => APP_PATH . '/grids/elements',
            'App\Forms' => APP_PATH . '/forms/',
            'App\Forms\Groups' => APP_PATH . '/forms/groups',
            'App\Plugins' => APP_PATH . '/plugins/',				
            'App\Helpers' => APP_PATH . '/helpers/',				
            'App\Components' => APP_PATH . '/components/',
	        'App\Extensions' => APP_PATH . '/extensions/',
        ))->register();
	}
	
	public function _initServices() {}
	
	
	/**
	 * Init locale.
	 *
	 * @param DI     $di     Dependency injection.
	 * @param Config $config Dependency injection.
	 *
	 * @return void
	 */
	protected function _initI18n() {
		$di = $this->_di;
		$config = $this->_configs;
		
		if (!$di->get('session')->has('language')) {
			$di->get('session')->set('language', 'en');
			$di->get('session')->set('locale', 'en');
		}
	
		$language = $di->get('session')->get('language');
		$messages = array();
		
		if(file_exists(APP_PATH . "/languages/". $language .".php")) {
			$messages = require APP_PATH . "/languages/". $language .".php";
		}
		
		$translate = new \Phalcon\Translate\Adapter\NativeArray(array("content" => $messages));
				
		$di->set('i18n', $translate);
	}
	
// 	public function _initNavigation() {
// 		$navigation = require APP_PATH. '/config/'. APP_ENV .'/navigation.php';
// 		$this->_di->set('navigation', function() use($navigation) {
// 			return $navigation;
// 		});
// 	}
	
	public function _initCache() {}
	
	public function _initLog() {}
	
}
