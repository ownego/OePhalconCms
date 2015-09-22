<?php
namespace OE\Application;

use Phalcon\Mvc\View as ViewPhalcon;
use OE\Behaviour\DebugBehaviour;

class View extends ViewPhalcon {
	
	use DebugBehaviour;	
}