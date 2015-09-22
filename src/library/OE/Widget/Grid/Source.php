<?php 
namespace OE\Widget\Grid;

use OE\Object;
use Phalcon;

class Source extends Object implements SourceInterface {
	
	const SEPERATOR = ',';
	
	public $builder;
	public $operator;
	public $column;
	public $search;
	
	public function __construct($builder) {
		$this->setBuilder($builder);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::search()
	 */
	public function search($column, $operator, $search, $filterMode=null, $index=null) {
		$this->column = $column;
		$this->operator = $operator;
		$this->search = $search;
		
		if($filterMode == 'model') {
			return;
		}
		
		if(is_array($search) && count($search) == 2) {
			$this->searchDateRange($index);
		} else {
			$operators = Filter::listOperators();
			if(isset($operators[$operator])) {
				$function = $operators[$operator];
				$function = str_replace(' ', '', $function);
				$function = lcfirst($function);
				$this->$function($index);
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchEqual()
	 */
	public function searchEqual($index) {
		$this->builder->andWhere($this->column. " = :$index:", array($index => $this->search));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchLike()
	 */
	public function searchLike($index) {
		$this->builder->andWhere($this->column. " LIKE :$index:", array($index => '%'. $this->search. '%'));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchStartEqual()
	 */
	public function searchStartEqual($index) {
		$this->builder->andWhere($this->column. " LIKE :$index:", array($index => $this->search. '%'));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchEndEqual()
	 */
	public function searchEndEqual($index) {
		$this->builder->andWhere($this->column. " LIKE :$index:", array($index => '%'. $this->search));
	}

	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchGreater()
	 */
	public function searchGreater($index) {
		$this->builder->andWhere($this->column. " > :$index:", array($index => $this->search));
	}

	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchLower()
	 */
	public function searchLower($index) {
		$this->builder->andWhere($this->column. " < :$index:", array($index => $this->search));
	}

	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchGreaterOrEqual()
	 */
	public function searchGreaterOrEqual($index) {
		$this->builder->andWhere($this->column. " >= :$index:", array($index => $this->search));
	}

	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchLowerOrEqual()
	 */
	public function searchLowerOrEqual($index) {
		$this->builder->andWhere($this->column. " <= :$index:", array($index => $this->search));
	}

	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchIn()
	 */
	public function searchIn($index) {
		if($search = $this->getSearchArr()) {
			$this->builder->inWhere($this->column, $search);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchNotIn()
	 */
	public function searchNotIn($index) {
		if($search = $this->getSearchArr()) {
			$this->builder->notInWhere($this->column, $search);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::searchBetween()
	 */
	public function searchBetween($index) {
		if(($search = $this->getSearchArr()) && count($search) == 2) {
			$this->builder->betweenWhere($this->column, $search[0], $search[1]);
		}
	}
	
	/*
	 * Search date range
	 * 	Only apply for date range filter
	 */
	public function searchDateRange($index) {
		$search = $this->search;
		$start = isset($search['start']) ? $search['start'] : null;
		$end = isset($search['end']) ? $search['end'] : null;
		
		if($start) {
			$start = date('Y-m-d', strtotime($start));
		}
		if($end) {
			$end = date('Y-m-d', strtotime($end));
		}
		
		if($start && $end) {
			$this->builder->andWhere($this->column. " >= :s$index:", array("s$index" => $start));
			$this->builder->andWhere($this->column. " <= :e$index:", array("e$index" => $end));			
		} 
		else if($start) {
			$this->builder->andWhere($this->column. " >= :$index:", array($index => $start));
		} 
		else if($end) {
			$this->builder->andWhere($this->column. " <= :$index:", array($index => $end));
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::setBuilder()
	 */
	public function setBuilder($builder) {
		$this->builder = $builder;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\SourceInterface::getBuilder()
	 */
	public function getBuilder() {
		return $this->builder;
	}
	
	/**
	 * Get array search
	 * @return boolean|multitype:
	 */
	protected function getSearchArr() {
		$search = explode(self::SEPERATOR, $this->search);
		if( ! is_array($search)) {
			return false;
		}
		return $search;
	}
}