<?php
/**
 *
 * @author farrelley
 * @version 
 */
require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * PropertyView Action Helper 
 * 
 * @uses actionHelper Zend_Controller_Action_Helper
 */
class Zend_Controller_Action_Helper_PropertyView extends Zend_Controller_Action_Helper_Abstract {
	/**
	 * @var Zend_Loader_PluginLoader
	 */
	public $pluginLoader;
	
	protected $_currency;
	protected $_date;
	
	/**
	 * Constructor: initialize plugin loader
	 * 
	 * @return void
	 */
	public function __construct() {
		// TODO Auto-generated Constructor
		$this->pluginLoader = new Zend_Loader_PluginLoader ( );
		$this->_currency = new Zend_Currency('en_US');
		$this->_date = new Zend_Date();		
	}
	
	/**
	 * Strategy pattern: call helper as broker method
	 */
	public function direct($data) {
		$newArray = array();
		foreach($data as $pKey => $pValue) {
			//echo $pKey . "<br />";
			switch($pKey) {
				case "Sale_Price" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Sale_Date" :
					if(is_null($pValue)) {
						$newArray[$pKey] = "N/A";
					} else {
						$this->_date->setTimezone('UTC');
						$this->_date->set($pValue, Zend_Date::ISO_8601);
						$this->_date->setTimezone('America/New_York');
						$newArray[$pKey] = $this->_date->get(Zend_Date::DATE_FULL);
					}
					break;
				case "Tax_Rate" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Base_Year_Land" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Base_Year_Building" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Prior_Land" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Prior_Improvment" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Prior_Total" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Current_Land" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Current_Improvements" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Current_Total" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Current_Phasein_Land" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				case "Current_Phasein_Building" :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $this->_currency->toCurrency($pValue);
					break;
				default :
					if(is_null($pValue))
						$newArray[$pKey] = "N/A";
					else
						$newArray[$pKey] = $pValue;	
			}
		}
		echo "<pre>";
		//var_dump($data);
		echo "</pre>";
		return $newArray;
	}
}

