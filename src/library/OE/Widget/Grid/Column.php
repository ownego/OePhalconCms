<?php

namespace OE\Widget\Grid;

use OE\Object;
use Phalcon\Mvc\Model\Row;
use OE\Widget\Grid\Filter\Text;
use OE\Widget\Grid\Filter\Date;
use OE\Widget\Grid\Filter\Select;
use OE\Widget\Grid\Filter\TextRange;
use OE\Widget\Grid\Filter\DateRange;
use OE\Widget\Grid\Filter\SelectRange;
use OE\Widget\Grid;
use OE\Widget\Grid\Filter\SelectStatic;

class Column extends Object {
	
	const OPERATOR_DEFAULT = 'like';
	
	public $name;
	public $header;
	public $filter;
	public $export;
	public $sort = true;
	public $value;
	public $valueExport;
	public $operators = array();
	public $operator;
	public $htmlOptions = array();
	public $headerHtmlOptions = array();
	public $html;
	public $gridName;
	public $filterMode = 'grid'; // Or filter in model
	public $beforeFilter;
	
	/**
	 * Parse parameters, init filter
	 * @param unknown $params
	 */
	public function __construct($params=array()) {
		$this->parseParams($params);
		$attrs = get_object_vars($this);
		
		foreach ($attrs as $attr => $val) {
			$method = 'set'. ucfirst($attr); 
			if(method_exists($this, $method)) {
				$this->$method($params[$attr]);
			} 
		}
		
		$this->initFilter($params);
	}
	
	/**
	 * Init filter
	 * @param unknown $params
	 */
	public function initFilter($params) {
		$filterObject = null;
		$filterParams = array(
			'name' => $this->gridName.'['. $this->name. ']',
			'key' => $this->name,
			'operator' => $this->getOperator()
		);
		 
		if(!isset($params['filter'])) {
			$filterObject = new Text();
		}
		else {
			$filter = $params['filter']; 
			if(is_array($filter)) {
				$filterParams['data'] = $params['filter'];
				$filterObject = new SelectStatic();				
			} 
			elseif($filter instanceof Date 
					|| $filter instanceof DateRange
					|| $filter instanceof Text
					|| $filter instanceof TextRange
					|| $filter instanceof Select
					|| $filter instanceof SelectRange
			) {
				$filterObject = $filter;
			}
		} 		
		
		if($filterObject) {
			$filterObject->addParams($filterParams);
			$filterObject->setOperators($this->getOperators());
			$filterObject->setFilterMode($this->getFilterMode());
			$this->setFilter($filterObject);
		}		
	}
	
	/**
	 * Parse params
	 * @param unknown $params
	 */
	public function parseParams(&$params) {
		$attrs = get_object_vars($this);
        foreach ($attrs as $attr => $val) {
			if( ! isset($params[$attr])) {
				$params[$attr] = null;
			}
		}
	}
	
	/**
	 * Get html 
	 * @param unknown $data
	 * @return Ambigous <string, unknown, mixed>
	 */
	public function getHtml($data) {
		$this->html = sprintf('<td %s>%s</td>', $this->getHtmlAttrs(), $this->getCellData($data));
		return $this->html;
	}
	
	/**
	 * Get cell data
	 * @return Ambigous <NULL, mixed, string>
	 */
	public function getCellData($data, $isExport=false) {
		$cellData = null;
		$value = $this->value;
		
		if($isExport && $this->valueExport) {
			$value = $this->valueExport;
		}
		if($data instanceof Row ) {
			if($value instanceof \Closure ) {
				$cellData = call_user_func($value, $data);
			} 
			elseif(is_string($value)) {
				$cellData = isset($data->$value) ? $data->$value : '';
			}
		}
		
		return $cellData;
	}
	
	/**
	 * Get search value by before filter option
	 * 
	 * @param unknown $search
	 * @return mixed
	 */
	public function getSearch($search) {
		$beforeFilter = $this->getBeforeFilter();
		if($beforeFilter instanceof \Closure) {
			$search = call_user_func($beforeFilter, $search);
		}
		return $search;
	}
	
	/**
	 * Get header html
	 * @param unknown $index
	 * @param unknown $order
	 * @param unknown $orderBy
	 * @return string
	 */
	public function getHeaderHtml($index, $order, $orderBy) {
		$header = $this->getHeader();
			
		if($this->getSort()) {
			$class = 'oe-sortable';
			$attrs = sprintf('data-order="%d"', $index+1);
			$icon  = null;
			$cIcon = 'up';
		
			if($order == $index+1) {
				$class .= ' sorting asc ';
				if($orderBy == Grid::ORDER_DESC) {
					$cIcon = 'down';
					$class .= 'desc';
				}
				$icon = sprintf('<i class="fa fa-caret-%s"></i>', $cIcon);
			}
			$html = sprintf('<th %s><a href="##" class="%s" %s>%s%s</a></th>', $this->getHeaderHtmlAttrs(), $class, $attrs, $header, $icon);
		} 
		else {
			$html = sprintf('<th %s>%s</th>', $this->getHeaderHtmlAttrs(), $header);
		}
		
		return $html;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getHeader() {
		$header = $this->header;
		if( $header instanceof \Closure ) {
			$header = call_user_func($header);
		}
		return $header ? $header : $this->name;
	}
	
	public function getHeaderExport() {
		if($this->getExport() == false) {
			return null;
		}
		$header = $this->header;
		if( $header instanceof \Closure ) {
			$header = call_user_func($header);
		}
		return $header;
	}
	
	public function getFilter() {
		return $this->filter;
	}
	
	public function getSort() {
		return $this->sort !== null ? $this->sort : true;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getValueExport() {
		return $this->valueExport;
	}
	
	public function getOperators() {
		return $this->operators;
	}
	
	public function getOperator() {
		return $this->operator ? $this->operator : self::OPERATOR_DEFAULT;
	}
	
	public function getHtmlOptions() {
		return $this->htmlOptions;
	}
	
	public function getHeaderHtmlOptions() {
		return $this->headerHtmlOptions;
	}	
		
	public function setGridName($gridName) {
		$this->gridName = $gridName;
		return $this;
	}
	
	public function getFilterMode() {
		return $this->filterMode;
	}
	
	public function getBeforeFilter() {
		return $this->beforeFilter;
	}
	
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	public function setHeader($header) {
		$this->header = $header;
		return $this;
	}
	
	public function setFilter($filter) {
		$this->filter = $filter;
		return $this;
	}
	
	public function setExport($export) {
		$this->export = $export;
		return $this;
	}
	
	public function getExport() {
		if(!isset($this->export)) {
			return true;
		}
		return $this->export;
	}
	
	public function setSort($sort) {
		$this->sort = $sort;
		return $this;
	}
	
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	public function setValueExport($valueExport) {
		$this->valueExport = $valueExport;
		return $this;
	}
	
	public function setOperators($operators) {
		$this->operators = $operators;
		return $this;
	}
	
	public function setOperator($operator) {
		$this->operator = $operator;
		return $this;
	}
	
	public function setHtmlOptions($htmlOptions) {
		$this->htmlOptions = $htmlOptions;
		return $this;
	}
	
	public function setHeaderHtmlOptions($headerHtmlOptions) {
		$this->headerHtmlOptions = $headerHtmlOptions;
		return $this;
	}
	
	public function setHtml($html) {
		$this->html = $html;
		return $this;
	}
	
	public function setFilterMode($filterMode) {
		$this->filterMode = $filterMode;
		return $this;
	}
	
	public function setBeforeFilter($beforeFilter) {
		$this->beforeFilter = $beforeFilter;
		return $this;
	}
	
	public function getHtmlAttrs() {
		return $this->_getAttrs($this->htmlOptions);
	}
	
	public function getHeaderHtmlAttrs() {
		return $this->_getAttrs($this->headerHtmlOptions);
	}
	
	private function _getAttrs($options) {
		$htmlAttrs = null;
		if(empty($options)) {
			return $htmlAttrs;
		}
		foreach ($options as $key => $value) {
			$htmlAttrs .= sprintf('%s="%s" ', $key, $value);	
		}
		return $htmlAttrs;		
	}
}