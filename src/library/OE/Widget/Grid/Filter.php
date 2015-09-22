<?php
namespace OE\Widget\Grid;

use OE\Object;
use Phalcon;
class Filter extends Object {
	
	const EMPTY_VALUE = '-';
	const EMPTY_TEXT = '---';
	
	public $search = null;
	public $name = null;
	public $key = null;
	public $data = null;
	public $html = null;
	public $source = null;
	public $operator = null;
	public $operators = array();
	public $filtering = false;
	public $filterMode = 'grid';
	public $filteringClass = 'oe-filtering';
	public $class = 'form-control';
	
	/**
	 * Init parameters
	 * @param array $params
	 */
	public function __construct($params=null) {
		if($params) {
			$this->initParams($params);
		}
	}
	
	/**
	 * Init params
	 * @param unknown $params
	 * @return \OE\Widget\Grid\Filter
	 */
	public function initParams($params) {
		$this->parseParams($params);
		$attrs = get_object_vars($this);
		
		foreach ($attrs as $attr => $val) {
			$method = 'set'. ucfirst($attr);
			if(method_exists($this, $method)) {
				$this->$method($params[$attr]);
			}
		}		
		
		return $this;
	}
	
	/**
	 * List of operators
	 * @return multitype:string
	 */
	public static function listOperators() {
		return array(
				'=' => 'Search Equal',
				'<' => 'Search Lower',
				'>' => 'Search Greater',
				'>=' => 'Search Greater Or Equal',
				'<=' => 'Search Lower Or Equal',
				'like' => 'Search Like',
				'start' => 'Search Start Equal',
				'end' => 'Search End Equal',
				'in' => 'Search In',
				'notIn' => 'Search Not In',
				'between' => 'Search Between',
		);
	}
	
	/**
	 * Get html list operators
	 * @return string
	 */
	public function getHtmlListOperators($key, $opt=null) {
		if(!$this->operators) {
			$this->operators = self::listOperators();
		}
	
		$html = Phalcon\Tag::hiddenField(array('opt['.$key.']', 'value' => $opt));
		$html .= '<button type="button" class="btn dropdown-toggle oe-btn-copt" data-toggle="dropdown" aria-expanded="false">';
		$html .= '<span>'. $opt .'</span></button>';
		$html .= '<ul class="oe-operators dropdown-menu">';
		foreach ($this->operators as $k => $v) {
			$html .= sprintf('<li><a href="##" data-opt="%s" class="oe-opt">%s</a></li>', $k, $this->_($v));
		}
		$html .= '</ul>';
	
		return $html;
	}
	
	/**
	 * Add params
	 * @param unknown $params
	 * @return \OE\Widget\Grid\Filter
	 */
	public function addParams($params) {
		$attrs = get_object_vars($this);
		foreach ($attrs as $attr => $val) {
			if( ! isset($params[$attr])) {
				unset($attrs[$attr]);
			}
		}		
		foreach ($attrs as $attr => $val) {
			$method = 'set'. ucfirst($attr);
			if(method_exists($this, $method)) {
				$this->$method($params[$attr]);
			}
		}
		return $this;
	}
	
	/**
	 * Run filter
	 */
	public function run($index=null) {
		$this->filtering = true;
		$this->source->search($this->key, $this->operator, $this->search, $this->filterMode, $index);
	}
	
	/**
	 * Parse parameters
	 * @param array $params
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
	 * Set source
	 * @param unknown $source
	 * @return \OE\Widget\Grid\Filter
	 */
	public function setSource($source) {
		$this->source = $source;
		return $this;
	}
	
	/**
	 * Set name of filter 
	 * @param unknown $name
	 * @return \OE\Widget\Grid\Filter
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Set key of filter as column alias
	 * @param unknown $key
	 * @return \OE\Widget\Grid\Filter
	 */
	public function setKey($key) {
		$this->key = $key;
		return $this;
	}
	
	/**
	 * Set text search
	 * @param unknown $search
	 * @return \OE\Widget\Grid\Filter
	 */
	public function setSearch($search) {
		$this->search = $search;
		return $this;
	}
	
	/**
	 * Set data
	 * @param unknown $data
	 * @return \OE\Widget\Grid\Filter
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}
	
	/**
	 * Set operator
	 * @param unknown $operator
	 * @return \OE\Widget\Grid\Filter
	 */
	public function setOperator($operator) {
		$this->operator = $operator;
		return $this;
	}
	
	public function setOperators($operators) {
		$this->operators = $operators;
		return $this;
	}
	
	public function setFilterMode($filterMode) {
		$this->filterMode = $filterMode;
		return $this;
	}
	
	public function getOperators() {
		$operators = array();
		if($this->operators) {
			foreach (self::listOperators() as $opt => $label) {
				if(in_array($opt, $this->operators)) {
					$operators[$opt] = $label;
				}
			}
		} else {
			$operators = self::listOperators();
		}
		return $operators;
	}
	
	/**
	 * Get Html
	 */
	public function getHtml() {
		$clearFilter = sprintf('<span class="%s"></span>', 'clear-filter');
		$this->operators = $this->getOperators();
		$this->html = sprintf('<div class="oe-filter btn-group %s">%s', $this->filtering ? $this->filteringClass : '', $this->filtering ? $clearFilter : '');
	}
}