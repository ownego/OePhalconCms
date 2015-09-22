<?php
namespace OE\Widget\Form\Validation;

use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation\Message;
use OE\Behaviour\TranslationBehaviour;
class Unique extends Validator implements ValidatorInterface {
	
	use TranslationBehaviour;
	
	public function validate($validator, $attribute) {
		$value = $validator->getValue($attribute);
		$model = $this->getOption('model');
		$column = $this->getOption('column');
		$id = $this->getOption('id');
		$condition = $this->getOption('condition');
		$bind = $this->getOption('bind');
		$operator = $this->getOption('operator');
		$operator = $operator ? $operator : ' AND ';
		$mode = $this->getOption('mode');
		
		$conditions = "$column = ?1";		
		if($mode == 'update') {
			$conditions .= " AND id != $id";
		}
		if($condition) {
			$conditions .= $operator . $condition;
		}		
		
		$binds = array(
			1 => $value
		);
		if($bind) {
			$binds += $bind;			
		}
		
		$object = $model::find(array(
			"conditions" => $conditions,
			"bind" => $binds	 
		));

		if($object->count()) {
			$message = $this->getOption('message');
			if(!$message) {
				$message = $this->_('The field is unique');
			}
			$validator->appendMessage(new Message($message, $attribute, 'unique'));
			return false;
		}
		
		return true;
	}
	
}