<?php
namespace OMCore;

class OMCrypto {
	var $_INIT_KEY = "";
	var $_INIT_VECTOR = "";
	var $_METHOD = "aes128";

	function __construct($init_key="0000000000000000", $init_vector="0000000000000000") {
		$this->_INIT_KEY = $init_key;
		$this->_INIT_VECTOR = $init_vector;
		if (!function_exists("mcrypt_cbc") && !function_exists("mcrypt_generic")) {
			$this->_METHOD = "";
		}
	}
	function pkcs5pad ($text, $blocksize){
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	function unpkcs5pad($text){
		$pad = ord($text{strlen($text)-1});
		if ($pad > strlen($text)) return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
		return substr($text, 0, -1 * $pad);
	}
	function Encrypt($data)
	{

	    $encrypted_string=openssl_encrypt($data,"AES-128-ECB",key_DB);
	   
	   	return $encrypted_string;
	}

	function Decrypt($data)
	{   
	    $decrypted_string=openssl_decrypt($data,"AES-128-ECB",key_DB);
	    return $decrypted_string;
	}
	// function Encrypt($data) {
	// 	if ($this->_METHOD == "aes128") {

	// 		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	// 	    mcrypt_generic_init($td, $this->_INIT_KEY,  $this->_INIT_VECTOR);
	// 	    $cipherText = mcrypt_generic($td, $this->pkcs5pad($data,32));

	// 	    mcrypt_generic_deinit($td);
	// 	    mcrypt_module_close($td);


	// 	} else {
	// 		$key = $this->_INIT_KEY;
	// 		$result = "";
	// 		for($i=0; $i<strlen($data); $i++) {
	// 			$char = substr($data, $i, 1);
	// 			$keychar = substr($key, ($i % strlen($key))-1, 1);
	// 			$char = chr(ord($char)+ord($keychar));
	// 			$result.=$char;
	// 		}
	// 		//TODO: use $result for no mcrypt
	// 		$cipherText = $data;
	// 	}
	// 	return base64_encode($cipherText);
	// 	// return "mysql";
	// }
	// function Decrypt($data) {
	// 	$cipherText  = base64_decode($data);
	// 	if ($cipherText === false) return false;
	// 	if ($this->_METHOD == "aes128") {

	// 		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
	// 	    mcrypt_generic_init($td, $this->_INIT_KEY,  $this->_INIT_VECTOR);
	// 	    $plainText = $this->unpkcs5pad(mdecrypt_generic($td, $cipherText));

	// 	    mcrypt_generic_deinit($td);
	// 	    mcrypt_module_close($td);

	// 	} else {
	// 		$key = $this->_INIT_KEY;
	// 		$result = "";
	// 		$string = $cipherText;
	// 		for($i=0; $i<strlen($string); $i++) {
	// 			$char = substr($string, $i, 1);
	// 			$keychar = substr($key, ($i % strlen($key))-1, 1);
	// 			$char = chr(ord($char)-ord($keychar));
	// 			$result.=$char;
	// 		}
	// 		//TODO: use $result for no mcrypt
	// 		$plainText = $cipherText;
	// 	}
	// 	// return "mysql";
	// 	return $plainText;
	// }


	public static function HashMD5($data) {
		return md5($data);
	}
}
?>