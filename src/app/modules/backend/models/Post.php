<?php
        		        		
namespace App\Modules\Backend\Models;

use App\Models\Post as PostBase;
use App\Helpers\Translate;

class Post extends PostBase {
    
    const STATUS_ACTIVE   = 1;
	const STATUS_INACTIVE = -1;
	const STATUS_DELETED  = -2;
        		
    public static function listStatus() {
		return array(
			self::STATUS_ACTIVE => Translate::t('Active'),
			self::STATUS_INACTIVE => Translate::t('Inactive'),
			self::STATUS_DELETED => Translate::t('Deleted'),
		);
	}
	
	public static function getStatusLabel($status) {
		$listStatus = self::listStatus();
		return isset($listStatus[$status]) ? $listStatus[$status] : null;
	}    		

    /**
     * @return 
     */
    public function search() { 
		$builder = $this->getModelsManager()
		->createBuilder()
		->columns(array('t.id', 't.category_id', 't.title', 't.quote', 't.content', 't.thumbnail', 't.link', 't.is_hot', 't.sort', 't.decscription', 't.created_date', 't.updated_date', 't.meta_title', 't.meta_description', 't.meta_keywords', 't.status'))
		->addFrom(__CLASS__, 't')
		->where('t.status > :status:', array('status' => self::STATUS_DELETED));
		
		return $builder;
	}

    /**
     * @return 
     */
    public function get($id) { 
		$builder = $this->getModelsManager()
		->createBuilder()
		->columns(array('t.id', 't.category_id', 't.title', 't.quote', 't.content', 't.thumbnail', 't.link', 't.is_hot', 't.sort', 't.decscription', 't.created_date', 't.updated_date', 't.meta_title', 't.meta_description', 't.meta_keywords', 't.status'))
		->addFrom(__CLASS__, 't')
		->where('t.id = :id:', array('id' => $id));
		
		return $builder;
	}

}
