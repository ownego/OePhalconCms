<?php

namespace OE\Widget;
use OE\Widget\Base;

class Paginator extends Base {
	
	// Total pages show in pagintion
	const TOTAL_PAGES_DISPLAY = 10;
	
	public $pageSizeOption = array(
		10 => 10,
		15 => 15,
		20 => 20,
		50 => 50,
		100 => 100,
		500 => 500,
		1000 => 1000,
		10000 => 10000
	);
	
	public $builder;
	
	public $page; 
	
	public $summaryTextFormat = '<div class="oe-summarytext"><p>Displaying %d-%d of %d results.</p></div>';
	
	public $pageSizeText = '(rows/page)';
	
	
	/**
	 * Constructor
	 * @param Builder $builder
	 */
	public function __construct($builder=null) {
		$this->setBuilder($builder);
	}
	
	/**
	 * Render html pagination
	 * @return string
	 */
	public function renderHtml() {
		$page = $this->builder->getPaginate()->current;
		$totalPages = $this->builder->getPaginate()->total_pages;
		if($totalPages <= 1) {
			return null;
		}
		
		$html = '<div class="oe-paginator"><ul class="pagination">';
		$html .= $this->_getPageItem(1, '&laquo;', null, 'first');
		if($page > 1) {
			$html .= $this->_getPageItem($page - 1, $this->_( 'Prev'), null, 'prev');
		}
		
		$pageStart = 1;
		$pageEnd   = self::TOTAL_PAGES_DISPLAY;
		$pageHalf  = floor(self::TOTAL_PAGES_DISPLAY/2);
		
		if($totalPages < self::TOTAL_PAGES_DISPLAY) {
			$pageEnd = $totalPages;	
		} 
		if($totalPages > self::TOTAL_PAGES_DISPLAY) {
			if($page >= self::TOTAL_PAGES_DISPLAY) {
				$pageStart = $page - $pageHalf;
				$pageEnd = $page + $pageHalf;
			}
		} 		
		if($page > $totalPages - (self::TOTAL_PAGES_DISPLAY - 1) 
			&& $totalPages > self::TOTAL_PAGES_DISPLAY) 
		{
			$pageStart = $totalPages - (self::TOTAL_PAGES_DISPLAY - 1);
			$pageEnd = $totalPages; 
		}
		for($i = $pageStart; $i <= $pageEnd; ++$i) {
			$active = ($page == $i) ? 'class="active"' : '';
			$html .= $this->_getPageItem($i, $i, $active);
		}
		if($page < $totalPages) {
			$html .= $this->_getPageItem($page + 1, $this->_('Next'), null, 'next');
		}
		$html .= $this->_getPageItem($totalPages, '&raquo;', null, 'last" data-page="'. $totalPages);
		$html .= '</ul></div>';
		
		return $html;
	}
	
	/**
	 * Render page size html
	 * @param unknown $uri
	 * @return string
	 */
	public function renderPageSize() {
		$html = '<div class="oe-pagesize"><select class="oe-form-control form-control">';
		foreach ($this->pageSizeOption as $value => $text) {
			$selected = null;
			if($value == $this->builder->getLimit()) {
				$selected = 'selected="selected"';
			} 
			$html .= sprintf('<option %s value="%s">%s</option>', $selected, $value, $text);
		}
		$html .= '</select>';
		$html .= '<p>'. $this->_($this->pageSizeText) .'</p>';
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Get summary text
	 * @return string
	 */
	public function renderSummaryText($disable=false) {
		$pageSize = $this->builder->getLimit();
		$page = $this->builder->getPaginate()->current;
		$totalItems = $this->builder->getPaginate()->total_items;
		
		$itemStart = $totalItems ? (($page-1)*$pageSize+1) : 0;
		$itemEnd = $page*$pageSize;
		$itemEnd = ($itemEnd > $totalItems) ? $totalItems : $itemEnd;
		
		return sprintf($this->_($this->summaryTextFormat), $itemStart, $itemEnd, $totalItems);
	}
	
	/**
	 * Get html item paginator
	 * @param unknown $page
	 * @return string
	 */
	private function _getPageItem($page, $text, $active, $class='item') {
		return sprintf('<li %s><a href="##" class="%s">%s</a>', $active, $class, $text);
	}
	
	/**
	 * Set builder
	 * @param unknown $builder
	 * @return \OE\Widget\Paginator
	 */
	public function setBuilder($builder) {
		$this->builder = $builder;
		return $this;
	}
	
	/**
	 * Get builder
	 * @return unknown
	 */
	public function getBuilder() {
		return $this->builder;
	}
}