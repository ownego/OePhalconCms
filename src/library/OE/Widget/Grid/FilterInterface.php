<?php 
namespace OE\Widget\Grid;

interface FilterInterface {
	
	public function run();
	
	public function setSource($source);
	
	public function setSearch($search);
	
	public function getHtml();
	
}