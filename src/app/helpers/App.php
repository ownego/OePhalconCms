<?php
namespace App\Helpers;

use OE\Object;
use Phalcon\DI;
class App extends Object {
	
	public static function import(array $classes) {
		$loader = new \Phalcon\Loader();
		$loader->registerClasses($classes)->register();
	}
	
	public static function getDatepickerFormat() {
	    return 'yy-mm-dd';
// 		return self::getLanguage() == 'ja' ? 'yy-mm-dd' : 'dd-mm-yy';
	}
	
	public static function getDateFormatPhp() {
	    return 'yy-mm-dd';
// 		return self::getLanguage() == 'ja' ? 'yy-mm-dd' : 'dd-mm-yy';
	}
	
	public static function getLanguage() {
		return DI::getDefault()->get('session')->get('language');		
	}
	
}