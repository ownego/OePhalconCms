<?php
namespace App\Modules\Backend\Forms\Groups;

use OE\Widget\Form\Group;
use OE\Widget\Form\Element\Button;

class BoxFooter extends Group {
	
    public $options = array();
        
    public function __construct($options = array()) {
        $this->options = $options;
                
        parent::__construct();
    }
    
	public function init() {
		$this->setName('box-footer');
		
		if ($this->options)
		    $this->setElements($this->options);
		else
            $this->setElements($this->_getElements());
		
		$this->setAttributes(array('class' => 'box-footer form-buttons col-md-12'));
	}
	
	protected function _getElements() {
		$submit = new Button('save');
		$submit->setLabel(_('Save'));
		$submit->setType('submit');
		$submit->addClass('btn btn-primary btn-submit');
		
		$reset = new Button('reset');
		$reset->setLabel(_('Reset'));
		$reset->setType('reset');
		$reset->addClass('btn btn-warning');
		
		return array($submit, $reset);
	} 
	
}