<?php
namespace OE\Widget\Form;

use Phalcon\Forms\Element;
use OE\Widget\Form\Element\Button;
use OE\Widget\Base;
use Phalcon\Tag;
class Group extends Base {
	
	public $name;
	public $elements = [];
	public $attributes = [];
	public $class = ['oe-form-box'];
	public $templateHtml;
	public $templateHtmlDefault = '<div id="%s" class="%s"%s>%s%s</div>';
	public $caption;
	public $captionClass = ['oe-form-group-caption'];
	public $captionTemplate = '<h3 class="%s">%s</h3>';
	
	/**
	 * Contructor
	 * @param string $name
	 * @param array $elements
	 * @param array $attributes
	 */
	public function __construct($name=null, $elements=null, $attributes=null) {
		$this->setName($name);		
		$this->setElements($elements);
		$this->setAttributes($attributes);
		$this->init();
		
	}
	
	public function init() {}
	
	/**
	 * Set name of form group
	 * 
	 * @param string $name
	 * @return \OE\Widget\Form\Group
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Set group caption
	 * 
	 * @param string $caption
	 * @return \OE\Widget\Form\Group
	 */
	public function setCaption($caption) {
		$this->caption = $caption;
		return $this;
	}
	
	/**
	 * Set attributes html
	 * 
	 * @param array $attributes
	 * @return \OE\Widget\Form\Group
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;
		return $this;
	}
	
	/**
	 * Set elements
	 * 
	 * @param array $elements
	 * @return \OE\Widget\Form\Group
	 */
	public function setElements($elements) {
		if(is_array($elements)) {
			foreach ($elements as $element) {
				$this->addElement($element);
			}
		}
		return $this;
	}
	
	/**
	 * Add element to form group
	 * 
	 * @param Phalcon\Form\Element $element
	 * @return \OE\Widget\Form\Group
	 */
	public function addElement($element) {
		if($element instanceof Element || $element instanceof Button) {
			$this->elements[$element->getName()] = $element;
		}
		return $this;
	}
	
	/**
	 * Add class
	 * 
	 * @param unknown $class
	 * @return \OE\Widget\Form\Group
	 */
	public function addClass($class) {
		$this->class[] = $class;
		return $this;
	}
	
	/**
	 * Set attribute html
	 * 
	 * @param unknown $attribute
	 * @param unknown $value
	 * @return \OE\Widget\Form\Group
	 */
	public function addAttribute($attribute, $value) {
		$this->attributes[$attribute] = $value;
		return $this; 
	}
	
	/**
	 * Remove element by name
	 * 
	 * @param string $name
	 * @return \OE\Widget\Form\Group
	 */
	public function removeElement($name) {
		if(isset($this->elements[$name])) {
			unset($this->elements[$name]);
		}
		return $this;
	}
	
	/**
	 * Get elements
	 * 
	 * @return array
	 */
	public function getElements() {
		return $this->elements;
	}
	
	/**
	 * Get attributes html
	 * 
	 * @return Ambigous <NULL, string>
	 */
	public function getAttributesHtml() {
		$attributesHtml = null;
		if(is_array($this->attributes)) {
			foreach ($this->attributes as $key => $value) {
				if($key == 'class') {
					continue;
				}
				$attributesHtml = sprintf(' %s="%s"', $key, $value);
			}
		}
		return $attributesHtml;
	}
	
	/**
	 * Set template html
	 * 	
	 * @example <div id="%s" class="%s"%s>%s</div>
	 * @param string $templateHtml 
	 * @return \OE\Widget\Form\Group
	 */
	public function setTemplateHtml($templateHtml) {
		$this->templateHtml = $templateHtml;
		return $this;		
	}
	
	/**
	 * Get template html
	 * 
	 * @return string
	 */
	public function getTemplateHtml() {
		$template = $this->templateHtml ? $this->templateHtml : $this->getTemplateHtmlDefault();
		$classHtml = $this->getClassHtml();
		$attributesHtml = $this->getAttributesHtml();
		return sprintf($template, $this->name, $classHtml, $attributesHtml, '%s', '%s');
	}
	
	/**
	 * Get template html default
	 * 
	 * @return string
	 */
	public function getTemplateHtmlDefault() {
		return $this->templateHtmlDefault;
	}
	
	/**
	 * Get class html of form group
	 * 
	 * @return string
	 */
	public function getClassHtml() {
		if(isset($this->attributes['class'])) {
			$this->class[] = $this->attributes['class'];
		}
		return implode(' ', $this->class);
	}
	
	/**
	 * Get name of form group
	 * 
	 * @return string name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Get group caption
	 * 
	 * @return string
	 */
	public function getCaption() {
		return $this->caption;
	}
	
	/**
	 * Render elements in form group
	 * 
	 * @param array $elements
	 * @return string
	 */
	public function renderElements($elements=null) {
		$html = '<div class="form-groups">';
		$elements = $elements ? $elements : $this->getElements();
		foreach ($elements as $element) {
			// Check isset required validator
			$element->checkIsRequired();
			$html .= $element->render();
		}
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Render group caption to html
	 * 
	 * @return Ambigous <NULL, string>
	 */
	public function renderCaptionHtml() {
		$html = null;
		if($caption = $this->getCaption()) {
			$html = sprintf($this->getCaptionTemplate(), $this->getCaptionClass(), $caption);
		}
		return $html;
	}
	
	/**
	 * Set caption template
	 * 
	 * @param unknown $captionTemplate
	 * @return \OE\Widget\Form\Group
	 */
	public function setCaptionTemplate($captionTemplate) {
		$this->captionTemplate = $captionTemplate;
		return $this;
	}
	
	/**
	 * Get caption template
	 * 
	 * @return string
	 */
	public function getCaptionTemplate() {
		return $this->captionTemplate;
	}
	
	/**
	 * Add caption class
	 * 
	 * @param unknown $class
	 * @return \OE\Widget\Form\Group
	 */
	public function addCaptionClass($class) {
		$this->captionClass[] = $class;
		return $this;
	}
	
	/**
	 * Get caption class
	 * 
	 * @return string
	 */
	public function getCaptionClass() {
		return implode(' ', $this->captionClass);
	}
	
	/**
	 * Render group to html
	 * 
	 * @return string
	 */
	public function render() {
		$elements = $this->renderElements($this->getElements());
		$caption  = $this->renderCaptionHtml();
		$template = $this->getTemplateHtml();
		return sprintf($template, $caption, $elements);
	}
}