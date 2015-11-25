<?php
/**
 * 
 * @author HoanNT
 *
 */
namespace OE\Widget;

use OE\Widget\Grid\Column;
use OE\Widget\Grid\ColumnGroup;
use OE\Widget\Paginator;
use OE\Widget\Paginator\Adapter\QueryBuilder;
use Phalcon;
use Phalcon\Paginator\Exception;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Query\Builder;
use OE\Widget\Grid\Source;
use OE\Widget\Grid\Filter;
use OE\Widget\Grid\PHPExcel\GridExcel;
use Phalcon\Mvc\View;

class Grid extends Base {

	const ORDER_DESC = 1;
	const ORDER_ASC = 2;
	public $page = 1;
	public $pageSize = 15;
	public $pageSizeMax = 1000000000;
	public $order;
	public $orderBy;
	public $html;
	public $source;
	public $data;
	public $columnGroup;
	public $columns;
	public $filters;
	public $header;
	public $body;
	public $footer;
	public $caption;
	public $displayCaption = false;
	public $template;
	public $name;
	public $request;
	public $params = array(); 
	public $operators = array(); 
	public $paginator;
	public $clearPaginator = false;
	public $ajaxRender = true;
	public $ajaxRequest = false;
	public $method = 'post';
	public $classTable = 'oe-table table table-bordered';
	public $classBtnFilter = 'oe-btn-filter oe-btn btn btn-primary';
	public $classBtnClearFilter = 'oe-btn-clear-filter oe-btn btn btn-danger';
	
	public $disablePagination = false;
	public $disableExport = false;
	public $disableTextSummary = false;
	public $disableFilter = false;
	
	public $exportExt = 'xlsx';
	public $exporting = false;
	public $uri; 
	
	public function __construct($name) {
		parent::__construct();
		$this->setName($name);
		$this->setRequest(new Request());
		$this->init();
	}
	
	public function init() {}
	
	public function run() {
		$request = $this->request; 
		
		if(!isset($request->get()[$this->name]) && $request->isAjax()) {
			return null;
		}
		
		$this->initParams();
		$this->initColumns();
		$this->initSource();
		$this->initFilters();
		$this->initData();
		
		if($this->exporting) {
			$phpExcel = new GridExcel($this->getData(), $this->getColumnGroup());
			$phpExcel->run($this->getExportExt());
		}
		
		if($request->isAjax()) {
			echo $this->_getContent();
			exit();	
		}
				
		return $this;
	}
	

	/**
	 * Init parameters
	 */
	public function initParams() {
		$params = $this->request->get();
		
		if(isset($params[$this->name])) {
			$this->params = $params[$this->name];
			$_SESSION['gridParams_'.$this->name] = $this->params;
		} else {
		    $this->params = isset($_SESSION['gridParams_'.$this->name]) ? $_SESSION['gridParams_'.$this->name] : null;
		}
		if(isset($params['opt'])) {
			$this->operators = $params['opt'];
			$_SESSION['gridOpt_'.$this->name] = $this->operators;
		} else {
			$this->operators = isset($_SESSION['gridOpt_'.$this->name]) ? $_SESSION['gridOpt_'.$this->name] : null;			
		}
		if(!empty($this->params['clearPaginator'])) {
			$this->clearPaginator = true;
		}
		if(!$this->disableExport && $this->request->isAjax() == false && $this->request->get('export') == true) {
			$this->exporting = true;
			$this->disablePagination = true;
		}
	}
	
	/**
	 * Init column group
	 */
	public function initColumns() {
		$columnGroup = new ColumnGroup();
		$columns = $this->columns;
		foreach ($columns as $col) {
			$col['gridName'] = $this->name;
			if(isset($col['name']) && ! empty($this->operators[$col['name']])) {
				$col['operator'] = $this->operators[$col['name']];
			}
			$columnGroup->addColumn(new Column($col));
		}
		$this->columnGroup = $columnGroup;
	}
	
	/**
	 * Init filters
	 */
	public function initFilters() {
		$filtering = false;
		foreach ($this->columnGroup->columns as $index=>$column) {
			if($column->filter) {
				if( ! isset($this->params[$column->name])) {
					continue;
				}
				$search = $this->params[$column->name];
				if($this->_hasSearch($search)) {
					$column->filter->setSource($this->source);
					$column->filter->setSearch($column->getSearch($search));
					$column->filter->run($index);
					if($filtering === false) $filtering = true; 
				}				
			}
		}
	}

	/**
	 * Check has filter
	 * @param unknown $search
	 * @return boolean
	 */
	protected function _hasSearch($search) {
		if(is_string($search)) {
			return ($search != null && $search != Filter::EMPTY_VALUE);
		} elseif(is_array($search)) {
			foreach ($search as $k => $v) {
				if($v != null && $v != Filter::EMPTY_VALUE) {
					return true;
				}
			}
		}
	}
	
	/**
	 * Init source
	 */
	public function initSource() {
		$order   = intval($this->getOrder());
		$orderBy = intval($this->getOrderBy());
		$columns = $this->columnGroup->columns;
		if(isset($columns[$order - 1])) {
			$column = $columns[$order - 1];
			$orderBy = $orderBy == self::ORDER_DESC ? 'DESC' : 'ASC';
			$this->source->getBuilder()->orderBy($column->name.' '.$orderBy);
		}
	}
	
	/**
	 * Init data to deploy grid view
	 */
	public function initData() {
		$limit = $this->disablePagination ? $this->pageSizeMax : $this->getPageSize();
		$page = $this->disablePagination ? 1 : $this->getPage();
		
		if($this->source->getBuilder() instanceof Builder) {
			$builder = new QueryBuilder(array(
					"builder" => $this->source->getBuilder(),
					"limit"=> $limit,
					"page" => $page
			));
			$this->paginator = new Paginator($builder);
		}
		
		// Other source type here
		if(empty($this->paginator)) {
			throw new Exception($this->_('Paginator must be not empty'));
		}
		
		$this->data = $this->paginator->builder->getPaginate()->items;
	}
	
	/**
	 * Render grid html
	 * @return string
	 */
	public function render($return=false) { 
		$content  = $this->_getContent();
		
		$html  = sprintf('<div class="oe-grid-container grid-%s" id="grid-%s">', $this->name, $this->name);
		$html .= sprintf('<form method="%s" action="%s" class="%s">', $this->method, $this->getUri(), $this->getClassForm());
		$html .= sprintf('%s</form></div>', $content);
		
		if(!$return) {
			echo $this->html,$html;
		}
		
		return $this->html.$html;
	}
	
	/**
	 * Get only content
	 * 
	 * @return string
	 */
	protected function _getContent() {
		$content = $this->renderTable();
		$content .= $this->renderHiddenInput();
		if(!$this->disablePagination) {
			$content .= $this->renderPaginator();
			$content .= $this->renderPageSize();
		}
		
		$html = $this->renderTopTool();
		$html .= sprintf('<div class="oe-grid-content">%s</div>', $content);
		
		return $html;
	}
	
	/**
	 * Render hidden input
	 * @return string
	 */
	public function renderHiddenInput() {
		$html  = Phalcon\Tag::hiddenField(array($this->name.'[page]', 'value' => $this->getPage()));
		$html .= Phalcon\Tag::hiddenField(array($this->name.'[pageSize]', 'value' => $this->getPageSize()));
		$html .= Phalcon\Tag::hiddenField(array($this->name.'[order]', 'value' => $this->getOrder()));
		$html .= Phalcon\Tag::hiddenField(array($this->name.'[orderBy]', 'value' => $this->getOrderBy()));
		$html .= Phalcon\Tag::hiddenField(array($this->name.'[clearPaginator]', 'value' => false));
		$html .= Phalcon\Tag::hiddenField(array($this->name.'[ajaxRenderLevel]', 'value' => View::LEVEL_NO_RENDER));
		return $html;
	}
	
	/**
	 * Render top tool
	 * @return string
	 */
	public function renderTopTool() {
		$html = '<div class="oe-grid-toptool">';
		if(!$this->disableExport) {
			$html .= $this->renderExportBox();
		}
		if(!$this->disableTextSummary) {
			$html  .= $this->renderSummaryText();
		}
		if($this->ajaxRender == false) {
			//$html .= sprintf('<div class="btn-group"><button type="button" class="%s">%s</button>', $this->classBtnFilter, $this->_('Filter'));
			//$html .= sprintf('<button type="button" class="%s">%s</button></div>', $this->classBtnClearFilter, $this->_('Clear Filter'));
		}
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * Render table html
	 * @return string
	 */
	public function renderTable() {
		$this->renderHeader();
		if(!$this->disableFilter) {
			$this->renderFilter();
		}
		$this->renderBody();
		$html = sprintf('<div class="oe-grid">%s', $this->displayCaption ? $this->renderCaption() : '');
		$html .= sprintf('<table class="%s">%s%s</table></div>', $this->classTable, $this->header, $this->body);
		return $html;
	}
	
	/**
	 * Render html pagination
	 * @return string
	 */
	public function renderPaginator() {
		if($this->disablePagination) {
			return null;
		}
		return $this->paginator->renderHtml();
	}
	
	/**
	 * Render page size options
	 * @return string
	 */
	public function renderPageSize() {
		return $this->paginator->renderPageSize();
	}
	
	/**
	 * Render table caption
	 * @return string 
	 */
	public function renderCaption() {
		return '<h1 class="oe-caption">'. $this->caption .'</h1>';
	}
	
	/**
	 * Render summary text
	 * @return string
	 */
	public function renderSummaryText() {
		return $this->paginator->renderSummaryText($this->disablePagination);
	}

	/**
	 * Render partial thead of table
	 */
	public function renderHeader() {
		$this->header = '<thead><tr>';
		foreach ($this->columnGroup->columns as $index => $column) {
			$this->header .= $column->getHeaderHtml($index, $this->getOrder(), $this->getOrderBy());
		}
		$this->header .= '</tr></thead>';
		
	}
	
	/**
	 * Render filter
	 */
	public function renderFilter() {
		$this->header .= '<tr class="oe-filters">';
		foreach ($this->columnGroup->columns as $column) {
			$this->header .= '<th>';
			if($column->filter) {
				$this->header .= $column->filter->getHtml();
			} 		
		}
	}
	
	/**
	 * Render partial tbody of table
	 */
	public function renderBody() {
		$this->body = '<tbody>';
		foreach ($this->getData() as $data) {
			$this->body .= '<tr>';
			foreach ($this->columnGroup->columns as $column) {
				$this->body .= $column->getHtml($data);
			}
			$this->body .= '</tr>';
		}
		$this->body .= '</tbody>';
	}
	
	/**
	 * Render export btn group
	 * @return string
	 */
	public function renderExportBox() {
		$gridExcelExt = GridExcel::$ext;
		
		$html = '<div class="oe-grid-export">';
		$html .= '<div class="btn-group">';
		$html .= '<button type="button" class="btn" data-toggle="dropdown">'. $this->_('Export') .'</button>';
		$html .= '<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
		$html .= '<span class="caret"></span></button>';
		$html .= '<ul class="dropdown-menu" role="menu">';
		foreach ($gridExcelExt as $ext => $name) {
			$html .= sprintf('<li class="export-ext" data-ext="%s"><a href="#">%s</a></li>', $ext, $name);
		}
		$html .= '</ul></div>';
		$html .= Phalcon\Tag::hiddenField(array('export-ext', 'value' => $this->getExportExt()));
		$html .= Phalcon\Tag::hiddenField(array('export', 'value' => false));
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Set grid name
	 * @param unknown $name
	 * @return \OE\Widget\Grid
	 */
	public function setName($name) {
        $this->name = lcfirst(preg_replace('/(\s|\n|\t)+/', '-', trim($name)));
		return $this;
	}
	
	/**
	 * Set request
	 * @param unknown $request
	 * @return \OE\Widget\Grid
	 */
	public function setRequest($request) {
		$this->request = $request;
		return $this;
	}
	
	/**
	 * Set grid caption
	 * @param unknown $caption
	 * @return \OE\Widget\Grid
	 */
	public function setCaption($caption) {
		$this->caption = $caption;
		return $this;
	}
	
	/**
	 * Set source
	 * @param unknown $builder
	 * @return \OE\Widget\Grid
	 */
	public function setSource($builder) {
		$this->source = new Source($builder);
		return $this;
	}
	
	/**
	 * Set columns
	 * @param unknown $columns
	 * @return \OE\Widget\Grid
	 */
	public function setColumns($columns) {
		$this->columns = $columns;
		return $this;
	}
	
	/**
	 * Set template to render
	 * @param unknown $template
	 * @return \OE\Widget\Grid
	 */
	public function setTemplate($template) {
		$this->template = $template;
		return $this;
	}
	
	/**
	 * Set column group
	 * @param unknown $columnGroup
	 * @return \OE\Widget\Grid
	 */
	public function setColumnGroup($columnGroup) {
		$this->columnGroup = $columnGroup;
		return $this;
	}
	
	/**
	 * Set page to paginate
	 * @param unknown $page
	 * @return \OE\Widget\Grid
	 */
	public function setPage($page) {
		$this->page = intval($page);
		return $this;
	}
	
	/**
	 * Set display caption
	 * @param string $display
	 * @return \OE\Widget\Grid
	 */
	public function setDisplayCaption($display=true) {
		$this->displayCaption = true;
		return $this;
	}
	
	/**
	 * Set ajax render
	 * @param unknown $isAjax
	 * @return \OE\Widget\Grid
	 */
	public function setAjaxRender($isAjax) {
		$this->ajaxRender = $isAjax;
		return $this;
	}
	
	/**
	 * Set disableExport
	 * @param string $isExport
	 * @return \OE\Widget\Grid
	 */
	public function setDisableExport($isExport=true) {
		$this->disableExport = $isExport;
		return $this;
	}
	
	/**
	 * Get data
	 * @return array $this->data
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Get page size
	 * @return number
	 */
	public function getPageSize() {
		return isset($this->params['pageSize']) ? intval($this->params['pageSize']) : $this->pageSize;
	}
	
	/**
	 * Get page
	 * @return number
	 */
	public function getPage() {
		$page = isset($this->params['page']) ? intval($this->params['page']) : $this->page;
		if(($this->clearPaginator && $page != 1) || !$page) {
			return 1;
		}
		return $page;
	}
	
	/**
	 * Get order
	 * @return integer $this->order
	 */
	public function getOrder() {
		return isset($this->params['order']) ? intval($this->params['order']) : $this->order;
	}
	
	/**
	 * Get order by
	 * @return integer $this->orderBy
	 */
	public function getOrderBy() {
		return isset($this->params['orderBy']) ? intval($this->params['orderBy']) : $this->orderBy;
	}
	
	/**
	 * Get column group
	 * @return ColumnGroup $this->columnGroup
	 */
	public function getColumnGroup() {
		return $this->columnGroup;
	}
	
	/**
	 * Get uri
	 * @return string $uri
	 */
	public function getUri() {
	    if($this->uri) {
	        return $this->uri;
	    }
		$baseUri = $this->getDI()->get('url')->getBaseUri();
		$rewriteUri = $this->getDI()->get('router')->getRewriteUri();
		
		if($baseUri !== '/') {
			return  $baseUri . $rewriteUri;
		}
		return $rewriteUri;
	}
	
	/**
	 * Get class of form
	 * @return string
	 */
	public function getClassForm() {
		return 'oe-grid-form'. ($this->ajaxRender ? ' oe-grid-form-ajax' : '');
	}
	
	/**
	 * Get export extension
	 * @return string
	 */
	public function getExportExt() {
		$request = $this->request->get();
		if(isset($request['export-ext']) && $exportExt = $request['export-ext']) {
			$this->exportExt = $exportExt;
		}
		return $this->exportExt;
	}
	
	/**
	 * Disable pagination
	 * 
	 * @param string $disable
	 * @return \OE\Widget\Grid
	 */
	public function setDisablePagination($disable=true) {
		$this->disablePagination = $disable;
		return $this;
	}
	
	/**
	 * Disable text summary
	 * 
	 * @param string $disable
	 * @return \OE\Widget\Grid
	 */
	public function setDisableTextSummary($disable=true) {
		$this->disableTextSummary = $disable;
		return $this;
	}
	
	/**
	 * Disable filter
	 * 
	 * @param string $disable
	 * @return \OE\Widget\Grid
	 */
	public function setDisableFilter($disable=true) {
		$this->disableFilter = $disable;
		return $this;
	}
	
	/**
	 * Set uri to post data
	 * 
	 * @param unknown $uri
	 * @return \OE\Widget\Grid
	 */
	public function setUri($uri) {
	    $this->uri = $uri;
	    return $this;
	}
}
