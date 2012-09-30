<?php
/**
 *
 * @author farrelley
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * IsoTime helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_IsoTime {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 *  
	 */
	public function isoTime($time) {
		$zd = new Zend_Date();
		$zd->setTimezone('UTC');
		$zd->set($time, Zend_Date::ISO_8601);		
		$zd->setTimezone('America/New_York');
		return $zd->get(Zend_Date::TIME_MEDIUM);
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
