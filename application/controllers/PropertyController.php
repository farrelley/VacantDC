<?php

class PropertyController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    	
    }

    public function indexAction()
    {
    	$properties = new Default_Model_Properties();
    	$propertiesView = new Default_Model_PropertiesView();

    	$zipcodes = $properties->fetchZipcodes();
    	$quadrants = $properties->fetchQuadrants();
    	$wards = $propertiesView->fetchWards();

    	$this->view->zipcodes = $zipcodes;
    	$this->view->quadrants = $quadrants;
		$this->view->wards = $wards;    
    }
    
    public function mapAction()
    {
		$this->_helper->layout->setLayout('layoutMap');
    	$properties = new Default_Model_Properties();
		$data = $properties->fetchMapData();
		$this->view->theJson = Zend_Json::encode($data, Zend_Json::TYPE_OBJECT);
    }
    
    public function viewAction()
    {
    	$auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()) {
        	$identity = $auth->getIdentity();
        } else {
        	$request = array(
        		'action' => $this->_request->getActionName(),
        		'controller' => $this->_request->getControllerName(),
        		'module' => $this->_request->getModuleName(),
        		'params' => $this->_request->getParams(),
        		'route' => $this->getFrontController()->getRouter()->getCurrentRouteName()
        	);
        	Zend_Registry::get('session')->redirect = $request;
        }
        	
    	$ssl = $this->getRequest()->getParam('ssl');

    	if(!isset($ssl))
    		$this->_redirect('/', array('reset' => true));
    
    	$userComments = new Default_Model_UserComments();
    	
    	if($identity) {
    		$date = new Zend_Date();
    		$date->setTimezone('UTC');
    		
	    	//get comment form
	    	$commentForm = new Default_Form_Comment();
	    	$this->view->commentForm = $commentForm;
	    	
	    	if($this->getRequest()->isPost()) {
	        	$formData = $this->getRequest()->getPost();
	        	if ($commentForm->isValid($formData)) {
	        		$values = $commentForm->getValues();
	        		$data=array(
	        			'User_Id'=>$identity->Email_Address,
	        			'SSL'=>$ssl,
	        			'Comment'=>$values['userComment'],
	        			'Create_Datetime'=>$date->get(Zend_Date::ISO_8601),
	        		);
	        		$userComments->addComment($data);
	        		$this->_helper->redirector('view', 'property', 'default', array('ssl'=>$ssl));
	        		
	        	}
	    	}
    	}
    	 
    	//get photos
    	$userPhotos = new Default_Model_UserPhotos();
    	$photos = $userPhotos->fetchPhotosBySSL($ssl, 6);
    	$this->view->userPhotos = $photos;

    	//get comments
    	$comments = $userComments->fetchCommentsBySSL($ssl);
    	$this->view->comments = $comments;

    	
    	//property information
    	$property = new Default_Model_PropertiesView();
    	$sslArray = $this->_helper->Ssl->parseSsl($ssl);
		$propertyData = $property->fetchProperty($sslArray['square'], $sslArray['suffix'], $sslArray['lot']);
		if(is_null($propertyData))
			$this->_redirect('/', array('reset' => true));
		
		$parsedPropertyData = $this->_helper->propertyView($propertyData);
    	$this->view->propertyData = $parsedPropertyData;
    	
    	//Other Owner properties
    	$ownerProperties = $property->fetchPropertiesByOwner($parsedPropertyData['Owner_Name'], $parsedPropertyData['Id']);
    	$this->view->ownerProperties = $ownerProperties;
    	
    	//get stats
    	$zillow = new Apps09_Api_Zillow();
    	$ZillowChart = $zillow->getZillowChartByZipCode($parsedPropertyData['Zipcode']);
    	$this->view->ZillowSalesChart = $ZillowChart;
    	
    }
    
    
    /*
     * listAction - gets a full list of all vacant properties in the database
     */
    public function listAction()
    {
    	$params = $this->getRequest()->getParams();
    	unset($params['controller']);
        unset($params['action']);
        unset($params['module']);
    	$key = array_keys($params);
    	
    	$properties = new Default_Model_PropertiesView();

    	switch($key[0]) {
    		case "ward":
    			$this->view->title = "Vacant Property Ward (" . $params['ward'] . ") List";
    			$this->view->vacantData = $properties->fetchPropertiesByWard($params['ward']);
    			break;
    		case "zipcode":
    			$this->view->title = "Vacant Property Zipcode (" . $params['zipcode'] . ") List";
    			$this->view->vacantData = $properties->fetchPropertiesByZipcode($params['zipcode']);
    			break;
    		case "quadrant":
    			$this->view->title = "Vacant Property Quadrant (" . $params['quadrant'] . ") List";
    			$this->view->vacantData = $properties->fetchPropertiesByQuadrant($params['quadrant']);
    			break;
    		default:
    			$this->view->title = "Vacant Property List";
    			$this->view->vacantData = $properties->fetchProperties();
    	}
    }
    
    public function submitAction()
    {
    	$api = new Default_Api_Authenicated();
    	if(!$api->isAuthenicated()) {
    		$request = array(
        		'action' => $this->_request->getActionName(),
        		'controller' => $this->_request->getControllerName(),
        		'module' => $this->_request->getModuleName(),
        		'params' => $this->_request->getParams(),
        		'route' => $this->getFrontController()->getRouter()->getCurrentRouteName()
        	);
        	Zend_Registry::get('session')->redirect = $request;
        	$this->_helper->redirector('login', 'auth', 'default');
    	}
    	
    	$this->view->title = "Submit a Vacant Lot";
    	//make the Open311 object
      	$open311 = new Apps09_Api_Open311();
    	$addressSearchForm = new Default_Form_SubmitAddress();
   		$this->view->addressSearchForm = $addressSearchForm;
    	
    	if ($this->getRequest()->isPost()) {
        	$formData = $this->getRequest()->getPost();
        	if ($addressSearchForm->isValid($formData)) {
        		//Get and Make the Address
        		$addressNumber = $addressSearchForm->getValue('addressNumber');
        		$streetName = $addressSearchForm->getValue('streetName');
        		$streetType = $addressSearchForm->getValue('streetType');
        		$quadrant = $addressSearchForm->getValue('quadrant');
        		$address = $addressNumber . " " . $streetName . " " . $streetType . " " . $quadrant;
  
    			$data = $open311->getSSLFromAddress($address);
    			$submittedSSL = $this->_helper->Ssl->parseSSL($data['SSL']);
    			
    			if(!$this->checkAlreadyVacant($submittedSSL['square'], $submittedSSL['suffix'], $submittedSSL['lot'])){
    				$this->view->status = true;
    				$this->view->data = $data;
    			}
    			else {
    				$this->view->status = false;
    				$this->view->data = $data;
    				$this->view->ssl = $submittedSSL;
    			}
        	} else {
        		$addressSearchForm->populate($formData);
        	}
        }
    }
    
    public function processsubmitAction()
    {
    	$open311 = new Apps09_Api_Open311();
    	$date = new Zend_Date();
    	$date->setTimezone('UTC');
    	
    	$this->_helper->viewRenderer->setNoRender(true);
    	$params = $this->getRequest()->getParams();
    	$desc = "The following potential property was submitted through vacantdc.com. Address Id: " . $params['aid'] . " SSL Code: " . $params['ssl'];
    	$token = $open311->submitVacantLot($params['aid'], $desc);
    	$auth = Zend_Auth::getInstance();
    	$identity = $auth->getIdentity();
    	
    	$data = array(
    		'User_Id'=>$identity->Email_Address,
    		'Token'=> $token,
    		'SSL'=>$params['ssl'],
    		'AID'=>$params['aid'],
    		'Create_Datetime'=>$date->get(Zend_Date::ISO_8601),
    		'Last_Updated_Datetime'=>$date->get(Zend_Date::ISO_8601),
    	);
    	
    	$userLots = new Default_Model_UserSubmittedLots();
    	$userLots->addLot($data);
    	
    	//set database here
    	$this->_redirect($this->_helper->url('success', 'property', 'default'));
    }
    
    public function successAction()
    {
    	$this->view->title = "Property Submitted";
    	$this->view->successMessage = "You have successfully submitted a property into the 311 system.
    	You can follow the status along via your account's page.";
    }
    
    private function checkAlreadyVacant($square, $suffix, $lot)
    {
    	$propertiesView = new Default_Model_PropertiesView();
    	$property = $propertiesView->fetchProperty($square, $suffix, $lot);
    	
    	if(is_null($property))
    		return false;
    	else
    		return true;
    }
    
    public function photouploadAction()
    {
    	
    	$api = new Default_Api_Authenicated();
    	if(!$api->isAuthenicated()) {
    		$request = array(
        		'action' => $this->_request->getActionName(),
        		'controller' => $this->_request->getControllerName(),
        		'module' => $this->_request->getModuleName(),
        		'params' => $this->_request->getParams(),
        		'route' => $this->getFrontController()->getRouter()->getCurrentRouteName()
        	);
        	Zend_Registry::get('session')->redirect = $request;
        	$this->_helper->redirector('login', 'auth', 'default');
    	}
    
		$params = $this->getRequest()->getParams();
    	$form = new Default_Form_UploadPhoto($params['ssl']);
        
    	$auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()) {
        	$identity = $auth->getIdentity();
        }
        	
     	if ($this->getRequest()->isPost()) {
        	$formData = $this->getRequest()->getPost();
        	if ($form->isValid($formData)) {      		
        		if ($form->photo->isUploaded()) {
        			$date = new Zend_Date();
        			$date->setTimezone('UTC');
        			
        			$userPhoto = new Default_Model_UserPhotos();
        			$photoFile = $form->photo->getFileInfo();
        			
        			$photoContent = file_get_contents($photoFile['photo']['tmp_name']);
        			$data = array(
        				'User_Id'=>$identity->Email_Address,
        				'SSL'=>$params['ssl'],
        				'Photograph'=>chunk_split(base64_encode($photoContent)),
        				'Content_Type'=>$photoFile['photo']['type'],
        				'Create_Datetime'=>$date->get(Zend_Date::ISO_8601),
        			);
        			$userPhoto->addPhoto($data);
        		} 
 	      		$this->_helper->redirector('view', 'property', 'default', array('ssl'=>$params['ssl']));
        	} else {
        		$form->populate($formData);
        	}
        }
    	
    	$this->view->form = $form;
    }
    
    public function photosAction()
    {
    	$params = $this->getRequest()->getParams();
    	$SSL = $this->_helper->Ssl->parseSSL($params['ssl']);
    	
    	$this->view->ssl = $params['ssl'];
    	//get the address
    	$properties = new Default_Model_Properties();
    	$address = $properties->fetchAddressBySSL($SSL);
    	$this->view->address = $address;
    	
    	$photos = new Default_Model_UserPhotos();
    	$userPhotos = $photos->fetchPhotosBySSL($params['ssl']);
    	$this->view->userPhotos = $userPhotos;
    }
    
 	public function imageAction()
    {
    	$params = $this->getRequest()->getParams();
    	$this->_helper->layout()->disableLayout();
    	$photo = new Default_Model_UserPhotos();
    	$thePhoto = $photo->fetchPhotoById($params['id']);
    	
    	$this->view->Content_Type = $thePhoto['Content_Type'];
    	$this->view->Photograph = $thePhoto['Photograph'];
    }
    

}

