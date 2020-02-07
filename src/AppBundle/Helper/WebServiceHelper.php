<?php
namespace AppBundle\Helper;

require(__DIR__.'/../api/JWT/src/BeforeValidException.php'); 
require(__DIR__.'/../api/JWT/src/ExpiredException.php'); 
require(__DIR__.'/../api/JWT/src/JWT.php'); 
require(__DIR__.'/../api/JWT/src/SignatureInvalidException.php'); 

use \Firebase\JWT\JWT;

class WebServiceHelper
{
    const _salt1 = "salt1";
	const _salt2 = "salt2";
	const _secretKey = "ebc";
    const _firm = "HS256";

    public static function decodeJWTToken($token){
		$token = trim(html_entity_decode($token),'""');
		$decoded = JWT::decode(
						$token, 
						self::_secretKey, 
						array(self::_firm)
					); 
		return $decoded;
	}

	public static function encodeJWTToken($token){
		$encoded = JWT::encode(
						$token, 
						self::_secretKey, 
						self::_firm
					); 
		return $encoded;
	}

	public static function customDecodeB64($value){
		$value = base64_decode($value);
		$value = str_replace(self::_salt1,'',$value);
		$value = str_replace(self::_salt2,'',$value);
		$value = strrev($value);
		return $value;
	}
	
	public static function customEncodeB64($value){
		$value = strrev($value);
		$value = base64_encode(self::_salt1.$value.self::_salt2);		
		return $value;
	}

}
?>