<?php
class Apps09_Controller_Action_Helper_Ssl extends Zend_Controller_Action_Helper_Abstract {
    
	public function parseSSL($ssl)
	{
		$sslArray = array();
		$sslArray['square'] = substr($ssl, 0, 4);
		
		$pattern = "/^[A-Za-z]{1}$/";
		$possibleSuffix = substr($ssl, 4, 1);
		if( preg_match($pattern, $possibleSuffix) ){
			$sslArray['suffix'] = substr($ssl, 4, 1);
			$sslArray['lot'] = substr($ssl, 5, 9);	
		}
		else {
			$sslArray['suffix'] = '';
			$sslArray['lot'] = substr($ssl, 4, 8);			
		}
	
		return $sslArray;
    }
}   