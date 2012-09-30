<?php 

/** Zend_Validate_Abstract */
require_once 'Zend/Validate/Abstract.php';    

class Apps09_Validate_PasswordStrength extends Zend_Validate_Abstract
{
    const TOO_SHORT  	  = 'passwordLengthTooShort';
    const TOO_FEW_UPPER   = 'passwordContainsTooFewUpper';
    const TOO_FEW_LOWER   = 'passwordContainsTooFewLower';
    const TOO_FEW_DIGITS  = 'passwordContainsTooFewDigits';
    const TOO_FEW_SPECIAL = 'passwordContainsTooFewSpecial';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::TOO_SHORT		  => "Value must be at least %minLength% characters in length",
        self::TOO_FEW_UPPER   => "Value must contain at least %minUpper% uppercase letter",
        self::TOO_FEW_LOWER   => "Value must contain at least %minLower% lowercase letter",
        self::TOO_FEW_DIGITS  => "Value must contain at least %minDigits% digit character",
        self::TOO_FEW_SPECIAL => "Value must contain at least %minSpecial% special charcters"
    );
    
    /**
     * @var array
     */
    protected $_messageVariables = array(
        'minLength'    => '_minLength',
        'minUpper'     => '_minUpper',
    	'minLower'     => '_minLower',
    	'minDigits'    => '_minDigits',
    	'minSpecial'   => '_minSpecial',
    	'specialChars' => '_specialChars'
    );
    
    /**
     * Minimum length
     * 
     * If 0, there is no minimum length
     * 
     * @var integer
     */
    protected $_minLength  = 8;
    
    /**
     * Minimum number of upper case characters
     * 
     * If 0, there is no minimum
     *  
     * @var integer
     */
    protected $_minUpper   = 1;
    
    /**
     * Minimum number of lower case characters
     * 
     * If 0, there is no minimum
     *  
     * @var integer
     */
    protected $_minLower   = 1;
    
    /**
     * Minimum number of digits
     * 
     * If 0, there is no minimum
     *  
     * @var integer
     */
    protected $_minDigits  = 1;
    
    /**
     * Minimum number of special characters
     * 
     * If 0, there is no minimum
     *  
     * @var integer
     */
    protected $_minSpecial = null;
    
    /**
     * Special Characters
     * 
     * @var string
     */
    protected $_specialChars = '!@#$%^*-+_=?|:';
    
    public function __construct($minLength = 8, $minUpper = 1, $minLower = 1, $minDigits = 1, $minSpecial = 0)
    {
        $this->setMinLength($minLength);
        $this->setMinUpper($minUpper);
        $this->setMinLower($minLower);
        $this->setMinDigits($minDigits);
        $this->setMinSpecial($minSpecial);
    }
    
    public function getMinLength()
    {
    	return $this->_minLength;
    }

    public function setMinLength($minLength)
    {
    	$this->_minLength = max(0, (integer) $minLength);
    	return $this;
    }

    public function getMinUpper()
    {
    	return $this->_minUpper;
    }
    
    public function setMinUpper($minUpper)
    {
    	$this->_minUpper = max(0, (integer) $minUpper);
    	return $this;
    }
    
    public function getMinLower()
    {
    	return $this->_minLower;
    }
    
    public function setMinLower($minLower)
    {
    	$this->_minLower = max(0, (integer) $minLower);
    	return $this;
    }
    
    public function getMinDigits()
    {
    	return $this->_minDigits;
    }

    public function setMinDigits($minDigits)
    {
    	$this->_minDigits = max(0, (integer) $minDigits);
    	return $this;
    }
    
    public function getMinSpecial()
    {
    	return $this->_minSpecial;
    }
    
    public function setMinSpecial($minSpecial)
    {
    	$this->_minSpecial = max(0, (integer) $minSpecial);
    	return $this;
    }

    public function getSpecialChars()
    {
    	return $this->_specialChars;
    }

    public function setSpecialChars($specialChars)
    {
    	$this->_specialChars = $specialChars;
    	return $this;
    }

    public function isValid($value)
    {
        $this->_setValue($value);

        $isValid = true;

        if (strlen($value) < $this->getMinLength()) {
            $this->_error(self::TOO_SHORT);
            $isValid = false;
        }

        if($this->getMinUpper() > 0) {
	        preg_match_all('/[A-Z]/', $value, $upperMatches);
	        if (count($upperMatches[0]) < $this->getMinUpper()) {
	            $this->_error(self::TOO_FEW_UPPER);
	            $isValid = false;
	        }
        }

        if($this->getMinLower() > 0) {
        preg_match_all('/[a-z]/', $value, $lowerMatches);
	        if (count($lowerMatches[0]) < $this->getMinLower()) {
	            $this->_error(self::TOO_FEW_LOWER);
	            $isValid = false;
	        }
        }

        if($this->getMinDigits() > 0) {
	        preg_match_all('/[0-9]/', $value, $digitMatches);
	        if (count($digitMatches[0]) < $this->getMinDigits()) {
	            $this->_error(self::TOO_FEW_DIGITS);
	            $isValid = false;
	        }
        }
        
        if($this->getMinSpecial() > 0) {
	        preg_match_all('/[' . $this->getSpecialChars() . ']/', $value, $specialMatches);
	        if (count($specialMatches[0]) < $this->getMinSpecial()) {
	        	$this->_error(self::TOO_FEW_SPECIAL);
	        	$isValid = false;
	        }
        }

        return $isValid;
    }
}