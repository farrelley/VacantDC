<?php
/**
 *
 * @author farrelley
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * BaseUrl helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_BaseUrl {
	
	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 *  
	 */
	public function baseUrl() {
        $fc = Zend_Controller_Front::getInstance(); 
        return $fc->getBaseUrl(); 
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
