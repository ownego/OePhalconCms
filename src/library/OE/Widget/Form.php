<?php
namespace OE\Widget;

use Phalcon\Forms\Form as PhalconForm;
use Phalcon\Tag;
use OE\Widget\Form\Group;
use OE\Behaviour\DebugBehaviour;
use OE\Behaviour\TranslationBehaviour;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator\PresenceOf;

class Form extends PhalconForm {
	
	use DebugBehaviour;
	use TranslationBehaviour;
	
	const GROUP_DEFAULT = 'oe-form-group-default';
	
	public $isAjaxSubmit = false;
	
	public $values = [];
	
	public $groups = [];
	
	public $method = 'POST';
	
	public $class = 'oe-form oe-form-common';
	
	public $attributes = [];
	
	public $hasCsrf = true;
	
	public $mode = 'create';
	
	
	/**
	 * @param Object Model $entity
	 * @param array $userOptions
	 */
	public function __construct($entity=null, $userOptions=null) {
		parent::__construct($entity, $userOptions);
		if(isset($userOptions['mode'])) {
			$this->mode = $userOptions['mode'];
		}
		$this->init();
		$this->_initGroup();
	}
	
	public function init() {}
	
	/**
	 * Add elements in form group to form object
	 */
	protected function _initGroup() {
		if($this->getGroups()) {
			foreach ($this->getGroups() as $group) {
				$elements = $group->getElements();
				foreach ($elements as $element) {
					$this->add($element);
				}
			}
		} else {
			$this->addGroup(new Group(
				self::GROUP_DEFAULT, 
				$this->getElements()
			));
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\Form::isValid()
	 */
	public function isValid($data=null, $entity=null) {
		$this->beforeValidate();
		$isValid = $this->csrfValidate();
		$isValid &= parent::isValid($data, $entity);
		$this->afterValidate($data);
		return $isValid;
	}
	
	/**
	 * Csrf validate
	 * 
	 * @return boolean
	 */
	public function csrfValidate() {
		if(!$this->hasCsrf) {
			return true;
		}
		return $this->security->checkToken();
	}
	
	public function beforeValidate() {}
	
	/**
	 * Set message to elements 
	 * 	apply after form validation
	 */
	public function afterValidate($data) {
		foreach ($this->getElements() as $element) {
			if($message = $this->getMessagesFor($element->getName())) {
				if($message instanceof \Phalcon\Validation\Message\Group) {
					foreach ($message as $m) {
						$element->addMessage($m->getMessage());
					}
				} 
				elseif($message instanceof Message) {
					$element->addMessage($message->getMessage());
				}
			}
			
			$name = $element->getName();
			$value = isset($data[$name]) ? $data[$name] : $this->getValue($name);
			if($filters = $element->getFilters()) {
				$value = $this->filter->sanitize($value, $filters);
			}
			$element->setValue($value);					
			$this->setValue($name, $value);
		}
	}
	
	/**
	 * Set value element
	 * 
	 * @param unknown $name
	 * @param unknown $value
	 * @return \OE\Widget\Form
	 */
	public function setValue($name, $value) {
		$this->values[$name] = $value;
		return $this; 
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\Form::getValue()
	 */
	public function getValue($name) {
		return isset($this->values[$name]) ? $this->values[$name] : parent::getValue($name);
	}
	
	/**
	 * Get all elements value
	 * 
	 * @return multitype:
	 */
	public function getValues() {
		$values = array();
		foreach ($this->getElements() as $element) {
			$name = $element->getName();
			$values[$name] = $this->getValue($name);
		}
		return $values;
	}
	
	/**
	 * Add formGroup
	 *
	 * @param OE\Widget\Form\Group $group
	 * @return \OE\Widget\Form
	 */
	public function addGroup($group) {
		if($group instanceof Group) {
			$this->groups[$group->getName()] = $group;
		}
		return $this;
	}
	
	/**
	 * Get form groups
	 *
	 * @return multitype:
	 */
	public function getGroups() {
		return $this->groups;
	}
	
	/**
	 * Get form group by name
	 *
	 * @param string $name
	 * @return Group
	 */
	public function getGroup($name) {
		return isset($this->groups[$name]) ? $this->groups[$name] : null;
	}
	
	/**
	 * Has csrf validation
	 * 
	 * @param string $hasCsrf
	 * @return \OE\Widget\Form
	 */
	public function setCsrf($hasCsrf=false) {
		$this->hasCsrf = $hasCsrf;
		return $this;
	}
	
	/**
	 * Render all elmements
	 * 
	 * @param string $name
	 * @param string $attributes
	 * @return string
	 */
	public function render($name=null, $attributes=null) {
		$html = $this->renderOpenTag();
		$html .= $this->renderGroups();
		$html .= $this->renderCloseTag();
		return $html;
	}
	
	public function renderCsrf() {
		return $this->hasCsrf ? Tag::hiddenField(array($this->security->getTokenKey(), 'value' => $this->security->getToken())) : null;
	}
	
	/**
	 * Render form group html
	 * 
	 * @return Ambigous <NULL, string>
	 */
	public function renderGroups() {
		$html = null;
		foreach ($this->getGroups() as $group) {
			$html .= $group->render();
		}
		return $html;
	}
	
	/**
	 * Render open form tag
	 * 
	 * @return string
	 */
	public function renderOpenTag() {
		$html = Tag::form(array($this->getAction(), 'method' => $this->getMethod(), 'class' => $this->class) + $this->attributes);		
		$html .= $this->renderCsrf();
		return $html;
	}
	
	/**
	 * Render close form tag
	 * 
	 * @return string
	 */
	public function renderCloseTag() {
		return Tag::endForm();
	}
	
	/**
	 * Set form method
	 * 
	 * @param unknown $method
	 * @return \OE\Widget\Form
	 */
	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}
	
	/**
	 * Get form method
	 * 
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}
	
	/**
	 * Get validation error message
	 * 
	 * @param String field name $field
	 * @return message
	 */
	public function getMessage($field) {
		$messages = $this->getMessages();
		if($messages && $messages->filter($field)) {
			return $messages->filter($field)[0]->getMessage();
		}
		return null;
	}
	
	/**
	 * Set form attribute 
	 * @param attribute name $name
	 * @param attribute value $value
	 * @return $this 
	 */
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
		return $this;
	}
	
	/**
	 * Get form attribute 
	 * @param attribute name $name
	 * @returni attribute value 
	 */
	public function getAttribute($name) {
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}	
}