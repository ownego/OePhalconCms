<?php
namespace App\Forms\Validations;

use Phalcon\Validation\Validator\PresenceOf;
use App\Helpers\Translate;
class AppPresenceOf extends PresenceOf {
	
	public function __construct($options) {
		if(!isset($options['message']) && isset($options['field'])) {
			$options['message'] = Translate::t('%m% is required', array('m' => Translate::t($options['field'])));
		}		
		parent::__construct($options);
	}
	
}