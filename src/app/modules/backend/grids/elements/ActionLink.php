<?php
namespace App\Modules\Backend\Grids\Elements;

use OE\Object;
use Phalcon\Tag;
use Phalcon\DI;

class ActionLink extends Object {
	
	public $data;
	
	public $options = array(
		'view' => array(
			'icon' => 'fa fa-eye',
			'link' => '',	
			'label' => 'View detail',
			'classLink' => 'oe-grid-action view'		
		),
		'update' => array(
			'icon' => 'fa fa-pencil-square-o',
			'link' => '',	
			'label' => 'Update item',
			'classLink' => 'oe-grid-action update'		
		),
		'delete' => array(
			'icon' => 'fa fa-trash-o',
			'link' => '',	
			'label' => 'Delete item',
			'classLink' => 'oe-grid-action delete',		
			'confirm' => 'Do you want to continue?'		
		),
		'baseUri' => null	
	);
	
	public $template = '{view}{update}{delete}';
	
	/**
	 * @param unknown $data
	 * @param unknown $options
	 */
	public function __construct($data, $options=array()) {
		$this->data = $data;
		if($options) {
			foreach ($this->options as $key=>$option) {
				if(isset($options[$key])) {
					$this->options[$key] = $options[$key];
				}
			}
		}
	}
	
	public function setOption($options) {
		$this->options = $options;
	} 
	
	/**
	 * Get links
	 * 
	 * @return Ambigous <NULL, string>
	 */
	public function getLinks() {
		$links = null;
		foreach ($this->options as $name => $option) {
			if($option && $name != 'baseUri') {
				$links .= $this->getLink($name);
			}
		}
		return $links;
	}
	
	/**
	 * Get link item
	 * 
	 * @param unknown $actionName
	 * @return NULL|string
	 */
	public function getLink($actionName) {
		if(!($option = $this->options[$actionName])) {
			return null;
		}
		$link = empty($option['link']) ? ($this->options['baseUri']. '/'. $actionName. "?id=". $this->data->id) : $option['link'];
		$icon = sprintf('<i class="%s"></i>', $option['icon']);
		$parameters = array(
			$link, 
			$icon, 
			'class' => $option['classLink'], 
			'title' => $this->_($option['label'])
		);
		if($actionName === 'delete') {
			$confirm = sprintf("return confirm('%s')", $this->_('Do you want to continue?'));
			$parameters['onClick'] = $confirm;
		} 
		return Tag::linkTo($parameters);
	}
}