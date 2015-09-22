<?php

namespace OE\Behaviour;

trait DebugBehaviour {
	
	protected function debug($param) {
		echo '<pre>';
		var_dump($param);
	}
	
	protected function debugdie($param) {
		echo '<pre>';
		var_dump($param);
		die(__LINE__);
	}
	
}