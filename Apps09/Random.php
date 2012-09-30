<?php

class Apps09_Random {

	/**
     * __construct() - Sets configuration options
     *
     * @return void
     */
	public function __construct()
	{

	}
	
	/**
	 * getRandom() - Gets random data
	 * 
	 * Adapted from the code found at 
	 * http://www.phptalk.net/2009/01/27/all-about-randomness-and-entropy/
	 * 
	 * @param int $bytes Number of bytes of random data to return.
	 * @return string
	 */
	public function getRandom($bytes)
	{
	    $rand = '';
	    
		// If OpenSSL random function is supported, we will use it
		if (function_exists('openssl_random_pseudo_bytes')) {
		    $rand = openssl_random_pseudo_bytes($bytes,$strong);
		    if ($strong === true) {
		        return $rand;
		    }
		}
		
		// If we are on Sun Solaris / Unix / Linux this should work
		if ( (strlen($rand) < $bytes) && ($handle = @fopen('/dev/urandom','rb')) ) {
		    $rand = fread($handle,$bytes);
		    fclose($handle);
	        return $rand;
		}
		
		// If both failed, let's try Microsoft's CSP
		if ( (strlen($rand) < $bytes) && (class_exists('COM')) ) {
		    $capi  = new COM('CAPICOM.Utilities.1');
		    $random = substr(base64_decode($capi -> getrandom($bytes,0)),0,$bytes);
		    unset($capi);
            return $rand;
		}
		
		// Everything failed?!
		if (strlen($rand) < $bytes) {
		    $rand = '';
		    for ($a = 0;$a < $bytes;$a += 16) {
		        $rand .= pack('H*',md5(mt_rand().microtime(true).uniqid('',true).join('',stat(__FILE__)).memory_get_usage().getmypid()));
            }
		    $rand = substr($rand,0,$bytes);
		}
		
		return $rand;	    
	}
	
}