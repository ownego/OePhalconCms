<?php
namespace OE\Widget\Grid\Filter;

use Phalcon;
use OE\Widget\Grid\Source;
use OE\Widget\Grid\Filter;
use OE\Widget\Grid\FilterInterface;

class DateRange extends Filter implements FilterInterface {
	
	// Date format default
	public $format = 'yy-mm-dd';
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\Filter::getHtml()
	 */
	public function getHtml() {
		$search = $this->search;
		$start = $search['start'];
		$end = $search['end'];
		$startClass = $this->class. ' datepicker date-range date-range-start';
		$endClass = $this->class. ' datepicker date-range date-range-end';
		
		parent::getHtml();
		$html = $this->html;
		$html .= Phalcon\Tag::textField(array($this->name.'[start]', 'value' => $start, 'class' => $startClass, 'data-format' => $this->format));
		$html .= "<div class='text-center'>~</div>";
		$html .= Phalcon\Tag::textField(array($this->name.'[end]', 'value' => $end, 'class' => $endClass, 'data-format' => $this->format));
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