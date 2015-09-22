<?php

namespace OE;

use OE\Behaviour\DIBehaviour;
use OE\Behaviour\DebugBehaviour;
use OE\Behaviour\TranslationBehaviour;

class Object {
	
	use DIBehaviour {
		DIBehaviour::__construct as protected __DIConstruct;
	}
	
	use DebugBehaviour,
		TranslationBehaviour;
	
	public function __construct($di=null) {
		$this->__DIConstruct($di);
	}
	
	public function __toString() {}
	
}