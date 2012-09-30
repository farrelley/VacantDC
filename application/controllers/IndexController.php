<?php

class IndexController extends Zend_Controller_Action
{
	/**
     * FlashMessenger
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger = null;
    
    /**
     * Redirector - defined for code completion
     *
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector = null;

    
    public function init()
    {
    	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    	$this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function indexAction()
    {
    	if( $this->_flashMessenger->setNamespace("AddressSearch")->hasMessages())
	    	$this->view->addressMessage = $this->_flashMessenger->setNamespace("AddressSearch")->getMessages();
    	
	    $blog = new Default_Model_Blog();
	    $this->view->blogArticle = $blog->fetchLastBlogPost();
	    
    	$addressSearchForm = new Default_Form_AddressSearch();
    	$this->view->addressSearchForm = $addressSearchForm;
    	
    	$submitAddressForm = new Default_Form_SubmitAddress();
    	$submitAddressForm->setAction('property/submit');
    	$this->view->submitAddressForm = $submitAddressForm;
   
	    if ($this->getRequest()->isPost()) {
        	$formData = $this->getRequest()->getPost();
        	if ($addressSearchForm->isValid($formData)) {
        		$addressNumber = $addressSearchForm->getValue('addressNumber');
        		$streetName = $addressSearchForm->getValue('streetName');
        		$streetType = $addressSearchForm->getValue('streetType');
        		$quadrant = $addressSearchForm->getValue('quadrant');
        		
        		$properties = new Default_Model_Properties();
        		$house = $properties->fetchSslByAddress($addressNumber, $streetName, $streetType, $quadrant);
        	
        		//house not found
        		if((bool)$house === false) {
        			$this->_flashMessenger->setNamespace("AddressSearch");
        			$this->_flashMessenger->addMessage("Address Not Found");
        			$this->_redirect('index');	
        		}
        		
        		if(count($house) > 1) {
        			$this->view->results = $house;
        			$this->render('indexResults');
        		} else {
        			$this->_redirector->gotoSimple(
        				'view',
        				'property',
        				null,
        				array(
        					'ssl' => $house[0]['Square'] . $house[0]['Suffix'] . $house[0]['Lot'],
        				)
        			);
        		}	
        	} else {
        		$addressSearchForm->populate($formData);
        	}
        }	
    }
}

