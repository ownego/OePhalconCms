<?php
namespace App\Components;

use OE\Object;
use App\Helpers\Translate;
use Phalcon\Mvc\Model\Query\Status;

class Constant extends Object {
	
	const MAIN_SITE = 'http://kissmovies.me/';
	
	const LANG_EN = 'en';
	const LANG_VI = 'vi';

	/**
	 * List or get language
	 * 
	 * @param string $key
	 * @return Ambigous <NULL, multitype:NULL >
	 */
	public static function listLang($key='') {
		static $data;
		if(!$data) {
			$data = array(
				self::LANG_EN => Translate::t('English'),
				self::LANG_VI => Translate::t('VietNam')
			);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
}
