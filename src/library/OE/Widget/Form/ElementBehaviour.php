<?php
namespace OE\Widget\Form;

use Phalcon\Tag;
use Phalcon\Validation\Message\Group;
use Phalcon\Validation\Validator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
trait ElementBehaviour {

	public $class = 'form-control';
	
	public $formGroupClass = 'form-group';
	public $formGroupcDatePicker = 'input-append date form_datetime';

	public $formGroupClassError = 'has-error';
	
	public $messageClass = 'message-error';
	
	public $messageBoxClass = 'message-box';
	
	public $formGroupAttributes;
	
	public $messages = [];
	
	public $required = false;
	
	public $validators = [];
	
	public $value;
	
	public $labelClass = "form-label";
	
	/**
	 * Template html
	 * 
	 * @var unknown
	 */
	public $templateHtml;
	
	/**
	 * Template html default
	 * 	<div class="form-group {class}" {attributes}>{label}{elementHtml}{messageError}</div>
	 * 
	 * @var unknown
	 */
	public $templateHtmlDefault = '<div class="{class}" {attributes}>{label}{element}{message}</div>';
	
	/**
	 * Set template html
	 * @param unknown $templateHtml
	 */
	public function setTemplateHtml($templateHtml) {
		$this->templateHtml = $templateHtml;
	}
	
	/**
	 * Get template html
	 * @return unknown
	 */
	public function getTemplateHtml() {
		return $this->templateHtml ? $this->templateHtml : $this->getTemplateHtmlDefault();
	} 
	
	/**
	 * Get template html default
	 */
	public function getTemplateHtmlDefault() {
		return $this->templateHtmlDefault;
	}
	
	/**
	 * Get label html
	 * 
	 * @param unknown $name
	 * @param string $id
	 * @return string
	 */
	public function getLabelHtml($name, $id=null) {
		$requiredHtml = null;
		if($this->getRequired()) {
			$requiredHtml .= Tag::tagHtml('i', array('class' => 'required'), true);
			$requiredHtml .= ' * ';
			$requiredHtml .= Tag::tagHtmlClose('i');
		}
		return $name ? sprintf('<label for="%s" class="%s">%s%s</label>', $id, $this->getLabelClass(), $name, $requiredHtml) : null;
	}
	
	/**
	 * Set label class 
	 * 
	 * @param unknown $class
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function setLabelClass($class) {
		$this->labelClass = $class;
		return $this;
	}
	
	/**
	 * Add class to label
	 * 
	 * @param unknown $class
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function addLabelClass($class) {
		$this->labelClass .= ' '.$class;
		return $this;
	}
	
	/**
	 * Get label class 
	 * 
	 * @return Ambigous <string, unknown>
	 */
	public function getLabelClass() {
		return $this->labelClass;
	}
	
	/**
	 * Set form group class
	 * 
	 * @param string $class
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function setFormGroupClass($class) {
		$this->formGroupClass = $class;
		return $this;
	}
	
	/**
	 * Add form group class
	 * 
	 * @param string $class
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function addFormGroupClass($class) {
		$this->formGroupClass .= ' '.$class;
		return $this;
	}
	
	/**
	 * Get form group class
	 * 
	 * @return string
	 */
	public function getFormGroupClass() {
		return $this->formGroupClass;
	}
	
	/**
	 * Set form group attributes html
	 * 
	 * @param string $attributes
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function setFormGroupAttributes($attributes) {
		$this->formGroupAttributes = $attributes;
		return $this;
	}
	
	/**
	 * Add form group attributes
	 * 
	 * @param string $attributes
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function addFormGroupAttributes($attributes) {
		$this->formGroupAttributes .= ' '.$attributes;
		return $this;
	}
	
	/**
	 * Get form group attributes
	 * 
	 * @return String $fromGroupAttributes
	 */
	public function getFormGroupAttributes() {
		return $this->formGroupAttributes;
	}
	
	/**
	 * Element class html
	 * 
	 * @param string $class
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function setClass($class) {
		$this->class = $class;
		return $this;
	} 
	
	/**
	 * Add element class html
	 * 
	 * @param string $class
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function addClass($class) {
		$this->class .= ' '.$class; 
		return $this;
	}
	
	/**
	 * Get element class html
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}
	
	/**
	 * Get attributes html
	 * 
	 * @param array $attributes
	 */
	public function getAttributesHtml($attributes) {
		if(isset($attributes['class'])) {
			$attributes['class'] .= ' '.$this->getClass(); 
		} else {
			$attributes['class'] = $this->getClass(); 			
		} 
		if($this->getMessages()) {
			$this->addFormGroupClass($this->formGroupClassError);
		}
		return $attributes;
	}
	
	/**
	 * Set message for element
	 * 	set after form validate
	 * 
	 * @param array $messages
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
// 	public function setMessages($messages) {
// 		if($messages instanceof Group) {
// 			$this->messages = $messages;
// 		}
// 		return $this;
// 	}
	
	/**
	 * Add message to messages array
	 * 
	 * @param unknown $message
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function addMessage($message) {
		$this->messages[] = $message;
		return $this;
	}
	
	/**
	 * Get message to render
	 * 
	 * @return unknown
	 */
	public function getMessages() {
		return $this->messages;
	}
	
	public function getMessagesHtml() {
		$html = null;
		if($messages = $this->getMessages()) {
			$html .= Tag::tagHtml('div', array('class' => $this->messageBoxClass), true);
			$html .= Tag::tagHtml('span', array('class' => $this->messageClass), true);
			$html .= $messages[0];
			$html .= Tag::tagHtmlClose('span');
			$html .= Tag::tagHtmlClose('div');
		}
		return $html;
	}
	
	/**
	 * Set required
	 * 
	 * @param string $required
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function setRequired($required=true) {
		$this->required = $required;
		return $this;
	}
	
	/**
	 * Get required
	 * 
	 * @return boolean
	 */
	public function getRequired() {
		return $this->required;
	}
	
	/**
	 * Set element value
	 * 
	 * @param unknown $value
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	/**
	 * Get element value
	 */
	public function getValue() {
		$value = parent::getValue();
		return empty($value) ? $this->value : $value;
	}
	
	/**
	 * Add validators
	 * 
	 * @param array $validators
	 * @param string $merge
	 */
	public function addValidators($validators, $merge = null) {
		$this->validators = $validators;
	}
	
	/**
	 * Add a validator
	 * 
	 * @param Validator $validator
	 * @return \OE\Widget\Form\ElementBehaviour
	 */
	public function addValidator($validator) {
		if($validator instanceof Validator) {
			$this->validators[] = $validator;
		}
		return $this;
	}
	
	/**
	 * Get all validators
	 * 
	 * @return multitype:
	 */
	public function getValidators() {
		return $this->validators;
	}
	
	/**
	 * Check isset required validator
	 */
	public function checkIsRequired() {
		if($validators = $this->getValidators()) {
			foreach ($validators as $validator) {
				if($validator instanceof PresenceOf) {
					$this->setRequired(true);
					continue;
				}
			}
		}
	}
	
	/**
	 * Render element html
	 * 
	 * @param string $attributes
	 * @return string
	 */
	public function render($attributes=null) {
		$attributes = $this->getAttributesHtml($this->getAttributes());
		$element = parent::render($attributes);
		$label = $this->getLabelHtml($this->getLabel(), $this->getName());
		$message = $this->getMessagesHtml();
		$formGroupClass = $this->getFormGroupClass();
		$formGroupAttributes = $this->getFormGroupAttributes();	
		$templateHtml = $this->getTemplateHtml();
		
		$html = str_replace('{class}', $formGroupClass, $templateHtml);
		$html = str_replace('{attributes}', $formGroupAttributes, $html);
		$html = str_replace('{label}', $label, $html);
		$html = str_replace('{element}', $element, $html);
		$html = str_replace('{message}', $message, $html);
		
		return $html;
	}
}