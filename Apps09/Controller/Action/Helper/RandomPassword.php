<?php

require_once 'Apps09/Random.php';

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

class Apps09_Controller_Action_Helper_RandomPassword extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * $_charLower - Lower case characters
     * 
     * @var string
     */
    protected $_charLower;
    
    /**
     * $_charUpper - Upper case characters
     * 
     * @var string
     */
    protected $_charUpper;
    
    /**
     * $_charNumeric - Numeric characters
     * 
     * @var string
     */
    protected $_charNumeric;
    
    /**
     * $_charSpecial - Special characters
     * 
     * @var string
     */
    protected $_charSpecial;
    
    /**
     * $_useCharTypes - Character types to use
     * 
     * @var array
     */
    protected $_useCharTypes = array();

    protected $_getRandomGenerator = null;
    
    /**
     * $_passwordLength - Length of the password to generate
     * 
     * @var int
     */
    protected $_passwordLength;
    
	public function __construct()
	{
        $this->setCharLower('a-z');
        $this->setCharUpper('A-Z');
        $this->setCharNumeric('0-9');
        $this->setCharSpecial('!@#$%^*-+_=?|:');
        
        $this->setUseLower(true);
        $this->setUseUpper(true);
        $this->setUseNumeric(true);
        $this->setUseSpecial(true);
        
        $this->setLength(8);
	}
	
	public function setCharLower($chars)
	{
	    if($chars != '')
	        $this->_charLower = $chars;
	    return $this;
	}
	
	public function setCharUpper($chars)
	{
	    if($chars != '')
	        $this->_charUpper = $chars;
	    return $this;
	}

	public function setCharNumeric($chars)
	{
	    if($chars != '')
	        $this->_charNumeric = $chars;
	    return $this;
	}

	public function setCharSpecial($chars)
	{
	    if($chars != '')
	        $this->_charSpecial = $chars;
	    return $this;
	}
	
	public function getChars()
	{
	    $chars = '';
	    if(is_array($this->_useCharTypes)) {
	        foreach($this->_useCharTypes AS $var) {
	            $chars .= $this->$var;
	        }
	    }
	    return $chars;
	}

	public function setUseLower($use)
	{
	    return $this->_setUseChar('_charLower', $use);
	}
	

	public function setUseUpper($use)
	{
	    return $this->_setUseChar('_charUpper', $use);
	}
	

	public function setUseNumeric($use)
	{
	    return $this->_setUseChar('_charNumeric', $use);
	}

	public function setUseSpecial($use)
	{
	    return $this->_setUseChar('_charSpecial', $use);
	}
	
	protected function _setUseChar($name, $use)
	{
	    switch($use) {
	        case true:
	            if(!in_array($name,$this->_useCharTypes)) {
	                $this->_useCharTypes[] = $name;
	            }
	            break;
	        case false:
	           if(($key = array_search($name))!==FALSE) {
	                unset($this->_useCharTypes[$key]);
	            }
	            break;
	    }
	    return $this;
	}
	
	public function setLength($length)
	{
	    if(($length !== true) && ((string)(int) $length) === ((string) $length)) {
	        $this->_passwordLength = intval($length);
	    }
	    return $this;
	}

	public function getLength()
	{
	    return $this->_passwordLength;
	}

	protected function _getRandomGenerator()
	{
	    if($this->_getRandomGenerator === null) {
	        $this->_getRandomGenerator = new Apps09_Random();
	    }
	    
	    return $this->_getRandomGenerator;
	}
	
	public function getRandomPassword($length = null)
	{
	    $chars = $this->getChars();
	    $random = $this->_getRandomGenerator();
	    if($length !== null) {
	        $this->setLength($length);
	    }
	    $length = $this->getLength();
	    
	    $password = '';

		while(strlen($password)<$length) {
		    preg_match_all("/[{$chars}]{1}/",$random->getRandom(8),$matches);
		    $password .= implode('',$matches[0]);

		    $password = ltrim($password, $this->_charSpecial);
		    $password = substr($password,0,$length);
		    $password = rtrim($password, $this->_charSpecial);
		}

		return $password;
	}
}