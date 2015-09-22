<?php 

namespace OE\Widget\Grid;

class ColumnGroup {
	
	public $columns=array();
	
	public function __construct($columns=array()) {
		$this->setColumns($columns);
	}
	
	public function setColumns($columns) {
		$this->columns = $columns;
		return $this;
	}
	
	public function addColumn($column) {
		if($column instanceof \OE\Widget\Grid\Column) {
			$this->columns[] = $column;
		}
		return $this;
	}
	
}
