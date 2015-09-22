<?php
namespace App\Helpers;

use OE\Object;
use Phalcon\DI;
class Translate extends Object {
	
	public static function t($msg, $args=null) {
		$di = DI::getDefault();
		$translate = $di->get('i18n');

		if(!$translate->exists($msg)) {
			self::addMessage($di, $msg);
		}
		
		return $translate->_($msg, $args);
	}
	
	public static function addMessage($di, $msg) {
		$language = $di->get('session')->get('language');
		$messagesFile = APP_PATH . "/languages/$language.php";
		$messages = require $messagesFile;
		
		$msgValue = str_replace('_', ' ', $msg);
		$messages[$msg] = $msgValue;
		@file_put_contents($messagesFile, 
		'<?php 
return ' . var_export((array) $messages, true) . ';');
	}
	
	public function _($msg, $args=null) {
		return self::t($msg, $args);
	}
}