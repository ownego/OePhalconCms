<?php
namespace OE\Widget\Grid\Filter;

use OE\Widget\Grid\FilterInterface;
use OE\Widget\Grid\Filter;
use Phalcon;
use OE\Widget\Grid\Source;

class Text extends Filter implements FilterInterface {
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\Filter::getHtml()
	 */
	public function getHtml() {
		parent::getHtml();
		$html = $this->html;
		$html .= Phalcon\Tag::textField(array($this->name, 'value' => $this->search, 'class' => $this->class));
		$html .= $this->getHtmlListOperators($this->key, isset($this->operators[$this->operator]) ? $this->operator : '');
		$html .= '</div>';	
		return $html;
	}
}

