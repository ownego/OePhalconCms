<?php
namespace App\Components;

use OE\Object;
use App\Helpers\Translate;
use Phalcon\Mvc\Model\Query\Status;

class Constant extends Object {
	
	const CONTRACT_TYPE_QUANTUM = 1;
	const CONTRACT_TYPE_LIFESTYLE = 2;
	
	const AGENCY_TYPE_A = 1;
	const AGENCY_TYPE_B = 2;

	const LANG_EN = 'en';
	const LANG_JA = 'ja';
	
	const SEX_MALE = 1;
	const SEX_FEMALE = 2;
	
	const MARIAGE_SINGLE = 1;
	const MARIAGE_MARRIED = 2;
	const MARIAGE_DIVOCED = 3;
	const MARIAGE_WIDOW = 4;
	
	const JOB_TYPE_WORKER = 1;
	const JOB_TYPE_OFFICER = 2;
	const JOB_TYPE_BIZMAN = 3;
	
	const PAYMENT_METHOD_CREDIT_CARD = 1;
	const PAYMENT_METHOD_BANK_TRANSFER = 2;
	
	const CARD_TYPE_VISA = 1;
	const CARD_TYPE_MASTER = 2;
	const CARD_TYPE_JCB = 3;
	
	const CURRENCY_EUR = 1;
	const CURRENCY_USD = 2;
	const CURRENCY_GBP = 3;
	
	const DOCUMENT_PASSPORT = 1;
	const DOCUMENT_DRIVER_LICENSE = 2;
	
	const CONTACT_ADDRESS_TYPE_HOME = 1;
	const CONTACT_ADDRESS_TYPE_WORK = 2;
	const CONTACT_ADDRESS_TYPE_OTHER = 3;
	
	const INVESTMENT_PLAN_MANY_TIMES = 1;
	const INVESTMENT_PLAN_ONE_TIMES = 2;	
	
	const PAYMENT_TYPE_FIRST_TIME_MONTHLY = 1;
	const PAYMENT_TYPE_FIRST_TIME_ONETIME = 2;
	
	const COMMISSION_TYPE_MONTHLY = 1;
	const COMMISSION_TYPE_QUARTER = 2;
	const COMMISSION_TYPE_HALF = 3;
	const COMMISSION_TYPE_ANNUAL = 4;
	
	const CONTRACT_YEAR_BETWEEN = 1;
	const CONTRACT_YEAR_FIVE = 2;
	const CONTRACT_YEAR_THIRTY_FIVE = 3;
	
	const DEL_TRUE = 2;
	const DEL_FALSE = 1;
	
	const BASE_URI = 'http://offshore.local/';
	
	const SHARE_NAME_YES = 1;
	const SHARE_NAME_NO = 0;
	
	const LA1_CHECKED_ON = 1;
	const LA1_CHECKED_OFF = 2;
	
	const UPLOAD_BASE_DIR = 'upload/';
	const UPLOAD_CMS_DIR = 'cms/';
	
	
	public static function listContractType($key='') {
		static $data;
		if (!$data) {
			$data = array(
					self::CONTRACT_TYPE_QUANTUM => Translate::t('Quantum'),
					self::CONTRACT_TYPE_LIFESTYLE => Translate::t('LifeStyle')
				);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listAgencyType($key='') {
		static $data;
		if(!$data) {
			$data = array(
				self::AGENCY_TYPE_A => Translate::t('A'),
				self::AGENCY_TYPE_B => Translate::t('B'),
			);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;   
	}
	
	public static function listLang($key='') {
		static $data;
		if(!$data) {
			$data = array(
				self::LANG_EN => Translate::t('English'),
				self::LANG_JA => Translate::t('Japanese')
			);
		}
		
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listSex($key='') {
		static $data;
		if (!$data) {
			$data = array(
					self::SEX_MALE => Translate::t('Male'),
					self::SEX_FEMALE => Translate::t('Female')
				); 
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listMariageStatus($short=false, $key='') {
		$data = array(
			self::MARIAGE_SINGLE => Translate::t('Single'),
			self::MARIAGE_MARRIED => Translate::t('Married')
		);
		if(!$short) {
			$data += array(
				self::MARIAGE_DIVOCED => Translate::t('Divoced'),
				self::MARIAGE_WIDOW => Translate::t('Widow')
			);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listJobType($key='') {
		static $data ;
		
		if (!$data) {
			$data = array(
						self::JOB_TYPE_WORKER => Translate::t('Worker'),
						self::JOB_TYPE_OFFICER => Translate::t('Officer'),
						self::JOB_TYPE_BIZMAN => Translate::t('Bizman')
					);
		}
		
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data; 
	}
	
	public static function listPaymentMethod($key='') {
		static $data;
		if (!$data) {
			$data = array(
					self::PAYMENT_METHOD_CREDIT_CARD => Translate::t('Credit card'),
					self::PAYMENT_METHOD_BANK_TRANSFER => Translate::t('Banktransfer')
			);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;  
	}
	
	public static function listCardType($key='') {
		static $data;
		if (!$data) {
			$data = array(
						self::CARD_TYPE_VISA => Translate::t('Visa'),
						self::CARD_TYPE_MASTER => Translate::t('Master'),
						self::CARD_TYPE_JCB => Translate::t('JCB')
					);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}	
	
	public static function listCurrency($key='') {
		static $data;
		if(!$data) {
			$data = array(
				self::CURRENCY_USD => Translate::t('USD'),
				self::CURRENCY_EUR => Translate::t('EUR'),
				self::CURRENCY_GBP => Translate::t('GBP')
			);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listDocumentType($key='') {
		static $data;
		if (!$data) {
			$data = array(
						self::DOCUMENT_PASSPORT => Translate::t('Passport'),
						self::DOCUMENT_DRIVER_LICENSE => Translate::t('Driver license')
					);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listContactAddressType($key='') {
		static $data;
		if (!$data) {
			$data = array(
						self::CONTACT_ADDRESS_TYPE_HOME => Translate::t('Home'),
						self::CONTACT_ADDRESS_TYPE_WORK => Translate::t('Work'),
						self::CONTACT_ADDRESS_TYPE_OTHER => Translate::t('Other')
					); 
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data; 
	}
	
	public static function listInvestmentPlan($key='') {
		static $data;
		if(!$data) {
			$data = array(
				self::INVESTMENT_PLAN_MANY_TIMES => Translate::t('Many times'),
				self::INVESTMENT_PLAN_ONE_TIMES => Translate::t('One times')
			);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data; 
	}
	
	public static function listPaymentTypeFirstTime($key='') {
		static $data = array();
		if (!$data) {
			$data = array(
					self::PAYMENT_TYPE_FIRST_TIME_MONTHLY => Translate::t('Monthly'),
					self::PAYMENT_TYPE_FIRST_TIME_ONETIME => Translate::t('One time'),
				);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listPaymentMethodFirstTime($key='') {
		static $data = array();
		if (!$data) {
			$data = array(
					self::PAYMENT_METHOD_CREDIT_CARD => Translate::t('Credit card'),
					self::PAYMENT_METHOD_BANK_TRANSFER => Translate::t('Banktransfer'),
				);
		}
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
	public static function listCommissionType($key='') {
		static $data;
		
		if (!$data) {
			$data = array(
						self::COMMISSION_TYPE_MONTHLY => Translate::t('Monthly'),
						self::COMMISSION_TYPE_QUARTER => Translate::t('Quarter'),
						self::COMMISSION_TYPE_HALF => Translate::t('Half'),
						self::COMMISSION_TYPE_ANNUAL => Translate::t('Annual')
					); 
		}
		
		return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
	}
	
    public static function listCountryCode($key='') {
    	static $data;
    	if (!$data) {
    		$data = array(
			            93 => 'Afghanistan (93)'
				        , 355 => 'Albania (355)'
				        , 213 => 'Algeria (213)'
				        , 684 => 'Samoa (684)'
				        , 376 => 'Andorra (376)'
				        , 244 => 'Angola (244)'
				        , '1-264' => 'Anguilla (1-264)'
				        , 672 => 'Norfolk Island (672)'
				        , '1-268' => 'Antigua and Barbuda (1-268)'
				        , 54 => 'Argentina (54)'
				        , 374 => 'Armenia (374)'
				        , 297 => 'Aruba (297)'
				        , 61 => 'Cocos (Keeling) Islands (61)'
				        , 43 => 'Austria (43)'
				        , 994 => 'Azerbaijan (994)'
				        , '1-242' => 'Bahamas (1-242)'
				        , 973 => 'Bahrain (973)'
				        , 880 => 'Bangladesh (880)'
				        , '1-246' => 'Barbados (1-246)'
				        , 375 => 'Belarus (375)'
				        , 32 => 'Belgium (32)'
				        , 501 => 'Belize (501)'
				        , 229 => 'Benin (229)'
				        , '1-441' => 'Bermuda (1-441)'
				        , 975 => 'Bhutan (975)'
				        , 591 => 'Bolivia (591)'
				        , 387 => 'Bosnia-Herzegovina (387)'
				        , 267 => 'Botswana (267)'
				        , 55 => 'Brazil (55)'
				        , 673 => 'Brunei Darussalam (673)'
				        , 359 => 'Bulgaria (359)'
				        , 226 => 'Burkina Faso (226)'
				        , 257 => 'Burundi (257)'
				        , 855 => 'Cambodia (855)'
				        , 237 => 'Cameroon (237)'
				        , 1 => 'USA (1)'
				        , 238 => 'Cape Verde (238)'
				        , '1-345' => 'Cayman Islands (1-345)'
				        , 236 => 'Central African Republic (236)'
				        , 235 => 'Chad (235)'
				        , 56 => 'Chile (56)'
				        , 86 => 'China (86)'
				        , 1 => 'Canada (1)'
				        , 57 => 'Colombia (57)'
				        , 269 => 'Mayotte (269)'
				        , 242 => 'Congo (242)'
				        , 243 => 'Congo, Dem. Republic (243)'
				        , 682 => 'Cook Islands (682)'
				        , 506 => 'Costa Rica (506)'
				        , 385 => 'Croatia (385)'
				        , 53 => 'Cuba (53)'
				        , 357 => 'Cyprus (357)'
				        , 420 => 'Czech Rep. (420)'
				        , 45 => 'Denmark (45)'
				        , 253 => 'Djibouti (253)'
				        , '1-767' => 'Dominica (1-767)'
				        , 809 => 'Dominican Republic (809)'
				        , 593 => 'Ecuador (593)'
				        , 20 => 'Egypt (20)'
				        , 503 => 'El Salvador (503)'
				        , 240 => 'Equatorial Guinea (240)'
				        , 291 => 'Eritrea (291)'
				        , 372 => 'Estonia (372)'
				        , 251 => 'Ethiopia (251)'
				        , 500 => 'Falkland Islands (Malvinas) (500)'
				        , 298 => 'Faroe Islands (298)'
				        , 679 => 'Fiji (679)'
				        , 358 => 'Finland (358)'
				        , 33 => 'France (33)'
				        , 594 => 'French Guiana (594)'
				        , 241 => 'Gabon (241)'
				        , 220 => 'Gambia (220)'
				        , 995 => 'Georgia (995)'
				        , 49 => 'Germany (49)'
				        , 233 => 'Ghana (233)'
				        , 350 => 'Gibraltar (350)'
				        , 44 => 'U.K. (44)'
				        , 30 => 'Greece (30)'
				        , 299 => 'Greenland (299)'
				        , '1-473' => 'Grenada (1-473)'
				        , 590 => 'Guadeloupe (French) (590)'
				        , '1-671' => 'Guam (USA) (1-671)'
				        , 502 => 'Guatemala (502)'
				        , 224 => 'Guinea (224)'
				        , 245 => 'Guinea Bissau (245)'
				        , 592 => 'Guyana (592)'
				        , 509 => 'Haiti (509)'
				        , 504 => 'Honduras (504)'
				        , 852 => 'Hong Kong (852)'
				        , 36 => 'Hungary (36)'
				        , 354 => 'Iceland (354)'
				        , 91 => 'India (91)'
				        , 62 => 'Indonesia (62)'
				        , 98 => 'Iran (98)'
				        , 964 => 'Iraq (964)'
				        , 353 => 'Ireland (353)'
				        , 972 => 'Israel (972)'
				        , 39 => 'Vatican (39)'
				        , 225 => 'Ivory Coast (225)'
				        , '1-876' => 'Jamaica (1-876)'
				        , 81 => 'Japan (81)'
				        , 962 => 'Jordan (962)'
				        , 7 => 'Russia (7)'
				        , 254 => 'Kenya (254)'
				        , 686 => 'Kiribati (686)'
				        , 850 => 'Korea-North (850)'
				        , 82 => 'Korea-South (82)'
				        , 965 => 'Kuwait (965)'
				        , 996 => 'Kyrgyzstan (996)'
				        , 856 => 'Laos (856)'
				        , 371 => 'Latvia (371)'
				        , 961 => 'Lebanon (961)'
				        , 266 => 'Lesotho (266)'
				        , 231 => 'Liberia (231)'
				        , 218 => 'Libya (218)'
				        , 423 => 'Liechtenstein (423)'
				        , 370 => 'Lithuania (370)'
				        , 352 => 'Luxembourg (352)'
				        , 853 => 'Macau (853)'
				        , 389 => 'Macedonia (389)'
				        , 261 => 'Madagascar (261)'
				        , 265 => 'Malawi (265)'
				        , 60 => 'Malaysia (60)'
				        , 960 => 'Maldives (960)'
				        , 223 => 'Mali (223)'
				        , 356 => 'Malta (356)'
				        , 692 => 'Marshall Islands (692)'
				        , 596 => 'Martinique (French) (596)'
				        , 222 => 'Mauritania (222)'
				        , 230 => 'Mauritius (230)'
				        , 52 => 'Mexico (52)'
				        , 691 => 'Micronesia (691)'
				        , 373 => 'Moldova (373)'
				        , 377 => 'Monaco (377)'
				        , 976 => 'Mongolia (976)'
				        , 382 => 'Montenegro (382)'
				        , '1-664' => 'Montserrat (1-664)'
				        , 212 => 'Morocco (212)'
				        , 258 => 'Mozambique (258)'
				        , 95 => 'Myanmar (95)'
				        , 264 => 'Namibia (264)'
				        , 674 => 'Nauru (674)'
				        , 977 => 'Nepal (977)'
				        , 31 => 'Netherlands (31)'
				        , 599 => 'Netherlands Antilles (599)'
				        , 687 => 'New Caledonia (French) (687)'
				        , 64 => 'New Zealand (64)'
				        , 505 => 'Nicaragua (505)'
				        , 227 => 'Niger (227)'
				        , 234 => 'Nigeria (234)'
				        , 683 => 'Niue (683)'
				        , 670 => 'Northern Mariana Islands (670)'
				        , 47 => 'Norway (47)'
				        , 968 => 'Oman (968)'
				        , 92 => 'Pakistan (92)'
				        , 680 => 'Palau (680)'
				        , 507 => 'Panama (507)'
				        , 675 => 'Papua New Guinea (675)'
				        , 595 => 'Paraguay (595)'
				        , 51 => 'Peru (51)'
				        , 63 => 'Philippines (63)'
				        , 48 => 'Poland (48)'
				        , 689 => 'Polynesia (French) (689)'
				        , 351 => 'Portugal (351)'
				        , '1-787' => 'Puerto Rico (1-787)'
				        , 974 => 'Qatar (974)'
				        , 262 => 'Reunion (French) (262)'
				        , 40 => 'Romania (40)'
				        , 250 => 'Rwanda (250)'
				        , 290 => 'Saint Helena (290)'
				        , '1-869' => 'Saint Kitts & Nevis Anguilla (1-869)'
				        , '1-758' => 'Saint Lucia (1-758)'
				        , 508 => 'Saint Pierre and Miquelon (508)'
				        , '1-784' => 'Saint Vincent & Grenadines (1-784)'
				        , 378 => 'San Marino (378)'
				        , 239 => 'Sao Tome and Principe (239)'
				        , 966 => 'Saudi Arabia (966)'
				        , 221 => 'Senegal (221)'
				        , 381 => 'Serbia (381)'
				        , 248 => 'Seychelles (248)'
				        , 232 => 'Sierra Leone (232)'
				        , 65 => 'Singapore (65)'
				        , 421 => 'Slovakia (421)'
				        , 386 => 'Slovenia (386)'
				        , 677 => 'Solomon Islands (677)'
				        , 252 => 'Somalia (252)'
				        , 27 => 'South Africa (27)'
				        , 34 => 'Spain (34)'
				        , 94 => 'Sri Lanka (94)'
				        , 249 => 'Sudan (249)'
				        , 597 => 'Suriname (597)'
				        , 268 => 'Swaziland (268)'
				        , 46 => 'Sweden (46)'
				        , 41 => 'Switzerland (41)'
				        , 963 => 'Syria (963)'
				        , 886 => 'Taiwan (886)'
				        , 992 => 'Tajikistan (992)'
				        , 255 => 'Tanzania (255)'
				        , 66 => 'Thailand (66)'
				        , 228 => 'Togo (228)'
				        , 690 => 'Tokelau (690)'
				        , 676 => 'Tonga (676)'
				        , '1-868' => 'Trinidad and Tobago (1-868)'
				        , 216 => 'Tunisia (216)'
				        , 90 => 'Turkey (90)'
				        , 993 => 'Turkmenistan (993)'
				        , '1-649' => 'Turks and Caicos Islands (1-649)'
				        , 688 => 'Tuvalu (688)'
				        , 256 => 'Uganda (256)'
				        , 380 => 'Ukraine (380)'
				        , 971 => 'United Arab Emirates (971)'
				        , 598 => 'Uruguay (598)'
				        , 998 => 'Uzbekistan (998)'
				        , 678 => 'Vanuatu (678)'
				        , 58 => 'Venezuela (58)'
				        , 84 => 'Vietnam (84)'
				        , '1-284' => 'Virgin Islands (British) (1-284)'
				        , '1-340' => 'Virgin Islands (USA) (1-340)'
				        , 681 => 'Wallis and Futuna Islands (681)'
				        , 967 => 'Yemen (967)'
				        , 260 => 'Zambia (260)'
				        , 263 => 'Zimbabwe (263)'
				        );
    	}
    	return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
    }
    
    public static function shareName($key='') {
    	static $data;
    
    	if (!$data) {
    		$data = array(
    				self::SHARE_NAME_NO => Translate::t('No'),
    				self::SHARE_NAME_YES => Translate::t('Yes'),
    		);
    	}
    
    	return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
    }
    
    public static function listLa1($key) {
    	static $data;
    	if (!$data) {
    		$data = array(
    				self::LA1_CHECKED_ON => Translate::t('On'),
    				self::LA1_CHECKED_OFF => Translate::t('Off')
    		);
    	}
    	return $key !== '' ? (isset($data[$key]) ? $data[$key] : null) : $data;
    }
    
    public static function getListQuantumPeriod($key){
        static $data;
        if (!$data) {
            $data = array(
                        1 => 'First time (60%)',
                        2 => 'Second time (20%)',
                        3 => 'Third time (20%)'
            );
        }
        return $key !== '' ? (isset($data[$key]) ? $data[$key] : $key) : $data;
    }
    
    public static function convertCurrency($currencyStart, $currencyEnd, $money) {
        static $currency = array();
        
        if (!$currency) {
            $currency = array(
                        self::CURRENCY_EUR => self::CURRENCY_EUR_PRICE,
                        self::CURRENCY_USD => self::CURRENCY_USD_PRICE,
                        self::CURRENCY_GBP => self::CURRENCY_GBP_PRICE 
                );
        }
        
        return $currency[$currencyStart] ? $currency[$currencyEnd]*$money/$currency[$currencyStart]:0;
    }
}