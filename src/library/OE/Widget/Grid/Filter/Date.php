<?php
namespace OE\Widget\Grid\Filter;

use OE\Widget\Grid\Filter;
use OE\Widget\Grid\FilterInterface;
use Phalcon;
use OE\Widget\Grid\Source;

class Date extends Filter implements FilterInterface {
	
	// Date format default
	public $format = 'yy-mm-dd';
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\Filter::getHtml()
	 */
	public function getHtml() {
		parent::getHtml();
		$html = $this->html;
		$html .= Phalcon\Tag::textField(array($this->name, 'value' => $this->search, 'class' => $this->class.' datepicker', 'data-format' => $this->format));
		$html .= $this->getHtmlListOperators($this->key, isset($this->operators[$this->operator]) ? $this->operator : '');
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Set date format
	 * @param unknown $format
	 * @return \OE\Widget\Grid\Filter\Date
	 */
	public function setFormat($format) {
		$this->format = $format;
		return $this;
	}
}