<?php
/**
 *
 * @author farrelley
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * IsoDate helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_IsoDate {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 *  
	 */
	public function isoDate($date) {
		$zd = new Zend_Date();
		$zd->setTimezone('UTC');
		$zd->set($date, Zend_Date::ISO_8601);		
		$zd->setTimezone('America/New_York');
		return $zd->get(Zend_Date::DATE_LONG);
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
