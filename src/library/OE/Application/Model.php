<?php
namespace OE\Application;

use OE\Behaviour\DebugBehaviour;
use OE\Behaviour\TranslationBehaviour;

class Model extends \Phalcon\Mvc\Model {

	use DebugBehaviour,
		TranslationBehaviour;
	
	public function validations() {
		return $this->validationHasFailed() != true;
	}
	
	public function save($data=null, $whiteList=null, $validate=false) {
		if($validate && !$this->validation()) {
			return false;
		}
		return parent::save($data, $whiteList);
	}
	
	public function getRequest() {
		return $this->getDI()->get('request');
	}
	
	public function getLanguage() {
		return $this->getDI()->get('session')->get('language');
	}
}