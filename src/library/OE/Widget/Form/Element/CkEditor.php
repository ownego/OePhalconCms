<?php
namespace OE\Widget\Form\Element;

use Phalcon;
use Phalcon\Tag;
use OE\Widget\Form\ElementBehaviour;
use Phalcon\Assets\Manager;
use Phalcon\DI;

class CkEditor extends TextArea {

	public function render($attributes=null) {
		$di = DI::getDefault();
		$assets = $di->get('assets');
		
		$assets->addJs('/skin/common/libs/ckeditor/ckeditor.js');
		$assets->addJs('/skin/common/libs/jquery/dist/jquery.min.js');
		echo "	<script>
					$('document').ready(function(){
						CKEDITOR.replace( '".$this->getName()."',{});
					});
				</script>";
		
		return parent::render($attributes);
	}
}
