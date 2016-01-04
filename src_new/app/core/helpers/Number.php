<?php
namespace App\Helpers;

use OE\Object;
class Number extends Object {

	/**
	 * Currency format
	 * 
	 * @param unknown $number
	 * @param string $symbol
	 * @param string $postSymbol
	 * @param string $preSymbol
	 * @return string
	 */
	public static function currencyFormat($number, $symbol=',', $postSymbol='', $preSymbol='') {
		if($number < 1000) {
			return $number;
		}
    	$format = number_format($number, 0, '.', $symbol);
    	
    	if($numberArr = explode('.', $number)) {
    		$decimal = isset($numberArr[1]) ? $numberArr[1] : null; 
    		if($decimal > 0) {
	    		$format = number_format($number, 2, '.', $symbol);
	    	}
    	}
    	
    	return $preSymbol. $format . $postSymbol;
    }
	
}