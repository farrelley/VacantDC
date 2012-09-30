<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	protected function _initFrontControllerSettings()
    {
        $this->bootstrap('frontController');
        Zend_Controller_Action_HelperBroker::addPrefix('Apps09_Controller_Action_Helper');
    }
    
	protected function _initAutoload()
	{
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Default_',
			'basePath' => APPLICATION_PATH)
		);
		
		$moduleLoader->addResourceType('api', 'api/', 'Api');
		
		//register the name space for autoloading
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('Apps09_');
	
		return $moduleLoader;
	}
	
	protected function _initCache()
	{
	}
	
	protected function _initRoutes() 
	{
	}
	
	protected function _initViewHelpers()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		
		$view->doctype('XHTML1_STRICT'); 
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8'); 
        $view->headTitle()->setSeparator(' - '); 
        $view->headTitle('vacantDC! :: Apps for Democracy');
	}
	
	protected function _initSession()
	{
		$session = new Zend_Session_Namespace('App09');
		Zend_Registry::set('session', $session);
	}
	
	protected function _initNavigation()
	{
	    $this->bootstrap('layout');
	    $layout = $this->getResource('layout');
	    $view = $layout->getView();
	    $config = new Zend_Config_Xml(APPLICATION_PATH.'/configs/navigation.xml');
	 
	    $navigation = new Zend_Navigation($config);
	    $view->navigation($navigation);
	}
	
	protected function _initPHPSettings()
	{
	}
}