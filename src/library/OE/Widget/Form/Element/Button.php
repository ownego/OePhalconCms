<?php
namespace OE\Widget\Form\Element;

use Phalcon\Forms\Element;
use Phalcon;
use Phalcon\Tag;
use OE\Widget\Form\ElementBehaviour;

class Button extends Element {
	
	use ElementBehaviour;
	
	public $name;
	public $type;
	public $attributes;
	
	public function __construct($name, $attributes=null) {
		$this->name = $name;
		$this->attributes = $attributes;
	}
	
	public function render($attributes=null) {
		$html = Tag::tagHtml('button', $this->getAttributesHtml());
		$html .= $this->getLabel();
		$html .= Tag::tagHtmlClose('button');
		return $html;
	}
	
	/**
	 * Get attributes html
	 * @return multitype:string string |array
	 */
	public function getAttributesHtml() {
		$attributesHtml = array(
			'name' => $this->name, 
			'type' => $this->getType(),
			'class' => str_replace('form-control', '', $this->getClass())	
		);
		if(empty($this->attributes)) {
			return $attributesHtml;
		}
		$attributesHtml += $this->attributes;
		
		return $attributesHtml;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\Element::getLabel()
	 */
	public function getLabel() {
		$label = parent::getLabel();
		$label = $label ? $label : (isset($this->attributes['label']) ? $this->attributes['label'] : $this->name);
		return $label;
	}
	
	/**
	 * Set button type
	 * 
	 * @param string $type
	 * @return \OE\Widget\Form\Element\Button
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}
	
	/**
	 * Get type of button
	 * 
	 * @return Ambigous <string, string>
	 */
	public function getType() {
		return $this->type ? $this->type : (isset($this->attributes['type']) ? $this->attributes['type'] : 'button');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Phalcon\Forms\Element::getName()
	 */
	public function getName() {
		return $this->name;
	}
}