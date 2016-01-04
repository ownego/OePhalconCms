<?php
namespace App\Modules\Backend\Forms;

use OE\Widget\Form;
use OE\Widget\Form\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use OE\Widget\Form\Element\Button;
use OE\Widget\Form\Element\Select;
use App\Components\Constant;
use OE\Widget\Form\Group;
use OE\Widget\Form\Element\Hidden;
use Phalcon\Validation\Validator\Identical;
use OE\Widget\Form\Element\Password;
class LoginForm extends Form {
	
	public function init() {
		$username = new Text('username');
		$username->setAttribute('placeholder', _('Username'));
		$username->addValidator(new PresenceOf(array('message' => _('Please enter your username'))));
		$username->setFilters(array('striptags', 'trim'));
		
		$password = new Password('password');
		$password->setAttribute('placeholder', _('Password'));
		$password->addValidator(new PresenceOf(array('message' => _('Please enter your password'))));
		$password->setFilters(array('striptags', 'trim'));
		
		$language = new Select('language');
		$language->setOptions(Constant::listLang());
		
		$submit = new Button('submit');
		$submit->setLabel(_('Sign in'));
		$submit->setType('submit');
		$submit->addClass('btn bg-olive btn-block');
		
		$group = new Group('login', array($username, $password, $language, $submit), array('class' => 'body bg-gray'));
		$this->addGroup($group);
	}
}