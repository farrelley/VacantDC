<?php
/**
 *
 * @author farrelley
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Property helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_Property {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 *  
	 */
	public function property($text) {
		return ucwords(strtolower($text)); 
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
