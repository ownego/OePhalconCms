<?php
namespace App\Components;


use OE\Object;
use Phalcon;
use Phalcon\DI;
use App\Helpers\Translate;

class Common extends Object {
	
	const SALT = '_oe_phalcon_cms_2015';
	
	public static function hash($value, $salt=null) {
		return md5($value.($salt ? $salt : self::SALT));	
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
     * Mcrypt encode data
     * 
     * @param unknown $data
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
