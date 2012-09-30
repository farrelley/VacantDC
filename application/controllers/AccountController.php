<?php

class AccountController extends Zend_Controller_Action 
{
    /**
     * @var Model_Users
     */
    protected $_userModel;
    
    /**
     * @var Zend_Auth
     */
    protected $_auth;
    
    /**
     * @var unknown_type
     */
	protected $_identity;
	
	/**
	 * @var int
	 */
	protected $_userId;
	
	/**
	 * FlashMessenger
	 * 
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	protected $_flashMessenger = null;

	/**
	 * init() - Instance of Zend_Controller_Action:init()
	 * 
	 * @see library/Zend/Controller/Zend_Controller_Action#init()
	 */
	public function init()
	{
		$this->_auth = Zend_Auth::getInstance();
        if($this->_auth->hasIdentity()) {
        	$this->_identity = $this->_auth->getIdentity();
        	$this->_userId = $this->_identity->Email_Address;
        	$this->view->identity = $this->_identity;
        	
        	if($this->_identity->TOS_ACK=='0') {
        		if($this->_request->getActionName() != 'tos') {
        			$this->_flashMessenger->addMessage('You must accept these Terms and Conditions to use this application.');
        			$this->_helper->redirector('tos', 'account', 'default');
        		}
        	} elseif($this->_identity->FORCE_PASS_CHANGE=='1') {
        		if($this->_request->getActionName() != 'changepass') {
        			$this->_flashMessenger->addMessage('You must change your password on first login.');
        			$this->_helper->redirector('changepass', 'account', 'default');
        		}
        	}
        } else {
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
        
	}
	
	/**
	 * indexAction() - Main account page action
	 *  
	 * @return null
	 */
    public function indexAction() 
    {
    	$submittedLots = new Default_Model_UserSubmittedLots();
    	$userModel = new Default_Model_Users();
    	
    	$user = $userModel->fetchUser($this->_userId);
    	$this->view->identity = $user;
    	
    	$lots = $submittedLots->fetchLotsByUserId($this->_userId);
    	$this->view->submittedLots = $lots;
    	
    }
    
    public function serviceidrequestAction() 
    {
    	$params = $this->getRequest()->getParams();
    	$userLots = new Default_Model_UserSubmittedLots();
    	$userLotId = $userLots->getIdByToken($params['token']);   	
    	$this->_helper->viewRenderer->setNoRender(true);
    	$open311 = new Apps09_Api_Open311();
    	$serviceId = $open311->getServiceIdFromToken($params['token']);
    	
    	$data = array(
    		'Service_Id'=>$serviceId
    	);
    	$userLots->setServiceRequestId($userLotId['Id'], $data);
		$this->_helper->redirector('property', 'account', 'default',array('id'=>$userLotId['Id']));
        }
    
    /**
     * changepassAction() - Change password action
     * 
     * @return null
     */
    public function changepassAction()
    {
        $request = $this->getRequest();
        $form    = new Default_Form_Account_Changepass();

        if($request->isPost()) {
            if ($form->isValid($request->getPost())) {
            	$values = $form->getValues();
            	
            	$userModel = new Default_Model_Users();
            	
            	$user = $userModel->fetchUserId($this->_identity->Id);
            	var_dump($user);
            	echo $values['Current_Password'];
            	echo hash('SHA256', $values['Current_Password']);
            	//echo $user['Password'] . "<br />";
            	if($user['Password'] == hash('SHA256', $values['Current_Password'])) {
                	$date = new Zend_Date();
			    	$updateArray = array();
			        $updateArray['Password'] = hash('SHA256', $values['New_Password']);
			        $updateArray['Force_Pass_Change'] = '0';
			        $updateArray['Last_Updated_Datetime'] = $date->getIso();
			        
			        $userModel->updateUser($this->_identity->Id, $updateArray);
			        
			        $ident = $this->_auth->getStorage()->read();
			        $ident->Force_Pass_Change = '0';
			        $this->_auth->getStorage()->write($ident);
	
			        $this->_helper->redirector('index', 'index');
                } else {
                	$form->getElement('Current_Password')->addError('Your current password is not valid.');
                }
		        
            }
        }
        
        $this->view->form = $form;
    }
    
    public function propertyAction()
    {
    	$params = $this->_request->getParams();
    	$property = new Default_Model_UserSubmittedLots();
    	$data = $property->fetchUserSubmitedProperty($params['id'], $this->_identity->Email_Address);
    	if(is_null($data)) {
    		$this->_helper->redirector('index', 'account');
    	}
    	
    	if($data['Service_Id'] != "") {
    		$open311 = new Apps09_Api_Open311();
    		$serviceStatus = $open311->getServiceIdStatus($data['Service_Id']);
    		
    		$updateData = array(
    			'Status'=>$serviceStatus['serviceorderstatus'],
    		);
    		$property->updateServiceIdStatus($params['id'], $updateData);
    	}
    	
    	//set view step
    	if($data['Token'] != "" && $data['Service_Id'] == "") 
	    	$this->view->step = '2';
	    if($data['Token'] != "" && $data['Service_Id'] != "") {
	    	$this->view->serviceStatus = $serviceStatus;
	    	$this->view->step = '3';	
	    }
		if($serviceStatus['serviceorderstatus'] == "OVERDUE CLOSED" || $serviceStatus['serviceorderstatus'] == "CLOSED") {
			$this->view->step = '5';
		}
    	if($serviceStatus['serviceorderstatus'] == "OVERDUE OPEN" || $serviceStatus['serviceorderstatus'] == "OPEN") {
			$this->view->step = '4';
		}
    	$this->view->property = $data;
    }
}


