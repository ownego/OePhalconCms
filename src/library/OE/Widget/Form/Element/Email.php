<?php

namespace OE\Widget\Form\Element;

use Phalcon\Forms\Element\Email as PhalconEmail;
use OE\Widget\Form\ElementInterface;
use OE\Widget\Form\ElementBehaviour;

class Email extends PhalconEmail implements ElementInterface {
	
	use ElementBehaviour;
	
}