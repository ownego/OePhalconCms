<?php

namespace OE\Behaviour;

use OE\Behaviour\DIBehaviour;
use Phalcon\DI;
use App\Helpers\Translate;

trait TranslationBehaviour {
	
	/**
	 * Translate message.
	 *
	 * @param string     $msg  Message to translate.
	 * @param array|null $args Message placeholder values.
	 *
	 * @return string
	 */
	protected function _($msg, $args = null) {
		if( ! ($di = $this->getDI())) {
			$di = DI::getDefault();
		}
		$translate = $di->get('i18n');
		if(!$translate->exists($msg)) {
			Translate::addMessage($di, $msg);
		}
		return $translate->_($msg, $args);
	}
}