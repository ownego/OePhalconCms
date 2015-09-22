<?php
namespace OE\Widget\Form;

use Phalcon\Forms\ElementInterface as PhalconElementInterface;

interface ElementInterface extends PhalconElementInterface {
	
	/**
	 * Set template html to render to html
	 * 
	 * @param string format $templateHtml
	 */
	public function setTemplateHtml($templateHtml);
	
	/**
	 * Get template html
	 * 
	 * @return string $templateHtml
	 */
	public function getTemplateHtml();
	
	/**
	 * Get template html default
	 * @return string $templateHtmlDefault
	 */
	public function getTemplateHtmlDefault();
	
} 