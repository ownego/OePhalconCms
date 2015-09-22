<?php
namespace App\Models;

use OE\Application\Model;

class Base extends Model {
	
	public function beforeCreate() {
		$this->created_at = $this->created_at ? $this->created_at : date('Y-m-d H:i:s');
	}
	
	public function beforeUpdate() {
		$this->updated_at = $this->updated_at ? $this->updated_at : date('Y-m-d H:i:s');
	}
	
}