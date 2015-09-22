<?php
namespace App\Helpers;

use OE\Object;
class FlashMessage extends Object {
	
	public $outputTemplate = '<div class="alert alert-%s alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>%s</div>';
	
	/**
	 * Output flash message by template
	 * 
	 * @return Ambigous <NULL, string>
	 */
	public function output() {
		$output = null;
		if($messages = $this->getDI()->get('flashSession')->getMessages()) {
			foreach ($messages as $key => $message) {
				foreach ($message as $m) {
					$output .= sprintf($this->outputTemplate, $key, $m);
				}	
			}
		}
		return $output;
	}
}