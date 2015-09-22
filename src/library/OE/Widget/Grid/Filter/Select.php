<?php
namespace OE\Widget\Grid\Filter;

use OE\Widget\Grid\Filter;
use OE\Widget\Grid\FilterInterface;
use OE\Widget\Grid\Source;
use Phalcon;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Tag;

class Select extends Filter implements FilterInterface {
	
	public $option;
	public $using;
	
	public function __construct($params=null) {
		parent::__construct($params);
		if($params[0] instanceof ResultsetInterface) {
			$this->option = $params[0];
		}
		if(isset($params['using'])) {
			$this->using = $params['using'];
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \OE\Widget\Grid\FilterInterface::getHtml()
	 */
	public function getHtml() {
		parent::getHtml();
		$html = $this->html;
		$html .= Tag::select(array($this->name, $this->option, 'using' => $this->using, 
				'value' => $this->search, 
				'class' => $this->class, 
				'useEmpty' => true, 
				'emptyText' => self::EMPTY_TEXT
		));
		$html .= '</div>';
		return $html;
	}
}