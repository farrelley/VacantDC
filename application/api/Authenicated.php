<?php

class Default_Api_Authenicated {
	
	public function isAuthenicated() 
	{
		$auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()) {
        	return true;
        } else {
			return false;
        }
	}
}

?>