<?php
namespace OE\Widget\Grid\Filter;

use OE\Widget\Grid\Filter;
use OE\Widget\Grid\FilterInterface;
use OE\Widget\Grid\Source;
use Phalcon;

class SelectStatic extends Filter implements FilterInterface {
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\FilterInterface::getHtml()
	 */
	public function getHtml() {
		parent::getHtml();
		$html = $this->html;
		$html .= self::getSelectStatic($this->name, $this->data, $this->search, array('class' => $this->class));
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Select box static
	 * @param unknown $name
	 * @param unknown $data
	 * @param unknown $search
	 * @param string $params
	 * @return string
	 */
	public static function getSelectStatic($name, $data, $search, $params=null) {
		$attrs = null;
		if($params) {
			foreach ($params as $attr => $value) {
				$attrs .= sprintf(' %s="%s"', $attr, $value);
			}
		}		
		if($search === NULL) {
			$search = self::EMPTY_VALUE;
		}
				
		$data = array(self::EMPTY_VALUE => self::EMPTY_TEXT) + $data;
		
		$html = "<select name='". $name ."' $attrs>";
		foreach ($data as $key => $value) {
			$selected = null;
			if($key == $search && $search !== self::EMPTY_VALUE) {
				$selected = 'selected="selected"';
			}
			$html .= sprintf('<option value="%s"%s>%s</option>', $key, $selected, $value);
		}
		$html .= '</select>';
		
		return $html;
	}
}