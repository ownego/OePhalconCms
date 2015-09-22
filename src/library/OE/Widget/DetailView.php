<?php
namespace OE\Widget;

use OE\Widget\Grid\Column;

class DetailView extends Grid {
	
	public $class = 'oe-detailview';
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid::render()
	 */
	public function render($return=false) {
		$html = '<div class="%s panel panel-default panel-grey">%s%s</div>';
		$html = sprintf($html, $this->class, $this->renderCaption(), $this->renderTable());
		if(!$return) {
			echo $this->html,$html;
		}
		return $html;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid::renderCaption()
	 */
	public function renderCaption() {
		if(!$this->caption) {
			return null;
		}
		$html = '<div class="panel-heading"><h3 class="panel-title"><i class="fa fa-tasks"></i> %s</h3></div>';
		return sprintf($html, $this->caption);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid::renderBody()
	 */
	public function renderBody($return=false) {
		$this->body = '<tbody>';
		foreach ($this->columnGroup->columns as $index => $column) {
			$this->body .= '<tr>';
			if($column instanceof Column) {
				$this->body .=  $column->getHeaderHtml($index, $this->getOrder(), $this->getOrderBy());
				
				if($this->getData()->count()) {
					foreach ($this->getData() as $data) {
						$this->body .= $column->getHtml($data);
					}
				} else {
					$this->body .= '<td></td>';
				}
			}
		}		
		if($return) {
			return $this->body;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid::renderTable()
	 */
	public function renderTable() {
		$html = '<div class="panel-body"><table class="table table-view">%s</table></div>';
		return sprintf($html, $this->renderBody(true));
	}
}