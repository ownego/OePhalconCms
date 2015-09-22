<?php

namespace OE\Widget\Form\Element;

use Phalcon\Forms\Element\Text as PhalconText;
use OE\Widget\Form\ElementInterface;
use OE\Widget\Form\ElementBehaviour;

class Text extends PhalconText implements ElementInterface {
	
	use ElementBehaviour;
	
}