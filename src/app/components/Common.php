<?php
namespace App\Components;


use OE\Object;
use Phalcon;
use Phalcon\DI;
use App\Helpers\Translate;

class Common extends Object {
	
	const SALT = 'oephalconcms_2015';
	
	/**
	 * Hash string by salt
	 * 
	 * @param string $value
	 * @param string $salt
	 * @return string
	 */
	public static function hash($value, $salt=null) {
		return md5($value.($salt ? $salt : self::SALT));	
	}  
    
    /**
     * Get back link
     * @return Ambigous <string, unknown>
     */
    public static function backLink() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
    
    /**
     * Format date by language
     * 
     * @param unknown $date
     * @param string $delimiter
     * @return string
     */
    public static function formartDateByLanguage($date, $delimiter='-') {
	    if(!$date) {
	 	    return '';
	    } 	
		$language = self::getLanguage();
	     
	    $time = strtotime(str_replace('/', '-', $date));
	     
	    return date('Y', $time) . $delimiter . date('m', $time) . $delimiter . date('d', $time);
    }
    
    /**
     * Get current language
     */
    public static function getLanguage() {
    	return DI::getDefault()->get('session')->get('language');
    }
    
    /**
     * Get user fullname by language
     * 
     * @param unknown $firstname
     * @param unknown $lastname
     * @param string $lang
     * @return string
     */
    public static function getUserFullname($firstname, $lastname, $lang=null) {
    	$lang = $lang ? $lang : self::getLanguage();
    	if($lang == Constant::LANG_EN) {
    		return $firstname.' '.$lastname;
    	} 
   		return $lastname.' '.$firstname;
    }
    
    /**
     * Upload files
     * 
     * @param file $files
     * @param string $subdir
     * @return uploaded files 
     */
    public static function uploadFiles($files, $subdir = '') {
    	$filenames = array();
    	foreach ($files as $file) {
    		$path = Constant::UPLOAD_BASE_DIR . $subdir . time() . '_' . strtolower($file->getName());
    		if($file->moveTo($path)) {
    			$filenames[] = '/' . $path;
    		}
    	}
    	return $filenames;
    }
    
    /**
     * 
     * @param string $name : button name
     */
    public static function genBackLink($name) {
        echo "<a class='btn btn-primary' href='javascript:history.back(1);' >$name</a>";
    }

    /**
     * Mcrypt encode data  
     * 
     * @param string $data
     * @return string
     */
    public static function mcrypt_encode($data) {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::SALT, $data, MCRYPT_MODE_ECB));
    }
    
    /**
     * Mcrypt decode data
     * 
     * @param unknown $data
     * @return string
     */
    public static function mcrypt_decode($data) {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::SALT, base64_decode($data), MCRYPT_MODE_ECB);
    }
}