<?php
namespace OE\Widget\Form\Element;

use Phalcon\Forms\Element\Hidden as PhalconHidden;
use OE\Widget\Form\ElementBehaviour;
use OE\Widget\Form\ElementInterface;

class Hidden extends PhalconHidden implements ElementInterface {
	
	use ElementBehaviour;
	
	public function render($attributes=null) {
		return parent::render($attributes);
	}
	
}