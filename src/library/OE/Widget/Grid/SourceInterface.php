<?php 
namespace OE\Widget\Grid;

interface SourceInterface {
	
	public function setBuilder($builder);
	
	public function getBuilder();
	
	public function search($column, $operator, $search);
	
	public function searchEqual($index);
	
	public function searchLike($index);
	
	public function searchStartEqual($index);
	
	public function searchEndEqual($index);
	
	public function searchGreater($index);
	
	public function searchGreaterOrEqual($index);
	
	public function searchLower($index);
	
	public function searchLowerOrEqual($index);
	
	public function searchIn($index);
	
	public function searchNotIn($index);
	
	public function searchBetween($index);
}