<?php

class AuthController extends Zend_Controller_Action 
{
	/**
	 * loginAction() - Login Action
	 * 
	 * @return null
	 */
	public function loginAction()
    {
		$request = $this->getRequest();
        $form = new Default_Form_Auth_Login();

        if($this->getRequest()->isPost()) {
			if ($form->isValid($request->getPost())) {
				$values = $form->getValues();
               
				$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());

				$authAdapter->setTableName('Users');
				$authAdapter->setIdentityColumn('Email_Address');
				$authAdapter->setCredentialColumn('Password');
				$authAdapter->setIdentity($values['username']);
				$authAdapter->setCredential(hash('SHA256', $values['password']));

				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);
                	
                if($result->isValid()) {
					$identity = $authAdapter->getResultRowObject(null, 'Password');
					$auth->getStorage()->write($identity);
                	
                    if($identity->Force_Pass_Change=='1') {
                    	$this->_helper->redirector('changepass', 'account', 'default');
		        	}
		        	
		        	$redirect = Zend_Registry::get('session')->redirect;
                    var_dump($redirect);
		        	if(is_array($redirect)) {
                    	Zend_Registry::get('session')->redirect = null;
                    	$this->_helper->redirector->gotoRoute($redirect['params'],$redirect['route'],true);
                    } else {
                    	$this->_helper->_redirector('index', 'index');
                    }
                } else {
                    $code = $result->getCode();
                    if($code === Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID || $code === Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND) {
                    	$this->view->message = "Login Failed!";
                    } else {
                    	$this->view->message = implode('<br />',$result->getMessages());
                    }
                }
            }
        }
        $this->view->form = $form;
    }
    
    /**
     * logoutAction() - Logout action
     * 
     * @return null
     */
    public function logoutAction()
    {
    	$identity = Zend_Auth::getInstance()->getIdentity();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->_redirector('index', 'index');
    }
    
	/**
	 * addAction() - Creates a new user account
	 * 
	 * @return null
	 */
    public function addAction()
    {
        $request = $this->getRequest();
        $form = new Default_Form_Auth_Add();
        
        if($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $userModel = new Default_Model_Users();
                                
                $user = $userModel->fetchUserByEmail($values['Email_Address']);
                if(is_array($user) && count($user)>0) {
                	$form->getElement('Email_Address')->addError('This e-mail address already exists in the system.  Please use a different e-mail address.');
                } else {
                	$date = new Zend_Date();
	                $password = $this->_helper->randomPassword->getRandomPassword(20);
	                $values['Password'] = hash('SHA256', $password);
	                $values['Force_Pass_Change'] = "1";
	                $values['Create_Datetime'] = $date->setTimezone('UTC')->getIso();
	                $values['Last_Updated_Datetime'] = $date->setTimezone('UTC')->getIso();
	               
	                $id = $userModel->addUser($values);
	                
			        $user = array(
			        	'Email_Address' => $values['Email_Address'],
			        	'First_Name' => $values['First_Name'],
			        	'Last_Name' => $values['Last_Name'],
			        	'Zip_Code' => $values['Zip_Code']
			        );

			        $config = array(
			        	'ssl' => 'tls',
			        	'port' => 587, 
			        	'auth' => 'login', 
			        	'username' => '', 
			        	'password' => ''
			       	);
			       
    				$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
    
			        $mail = new Zend_Mail();
					$mail->setBodyText("Your account has been created.  Your temporary password has been se to " . $password . "  You will be  asked to reset this when you first login.  If you have any questions or problems please let us know at Questions@vacantdc.com");
	        		$mail->setFrom('noreply@vacantdc.com','vacantDC');
	        		$mail->addTo($values['Email_Address'], $values['First_Name'] . ' ' . $values['Last_Name']);
	        		$mail->setSubject('vacantDC! | Your Account Password');
					$mail->send($smtpConnection);
	                return $this->_helper->redirector('login', 'auth');
                }
            }
        }
        $this->view->form = $form;
    }
    
    public function resetAction()
    {
   		$request = $this->getRequest();
        $form = new Default_Form_Auth_Reset();
        
        if($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                
                $userModel = new Default_Model_Users();
                $user = $userModel->fetchUserByEmail($values['Email_Address']);
                if($user!==null) {
                	
                	$this->_resetPassword($user['Id']);
                	
                	$this->_helper->redirector('login', 'auth');	
                }
                else {
                	echo "unable to find user in system";
                }
            }
        }
        $this->view->form = $form;
    }                
                
    
    /**
     * _resetPassword() - Reset a users password
     * 
     * @todo Error checking fo no user.
     * @param int|string $id USER_ID of the user to modify
     */
    protected function _resetPassword($id)
    {
    	
        $password = $this->_helper->randomPassword->getRandomPassword(20);
        
		$values = array();
        $values['Password'] = hash('SHA256', $password);
        $values['Force_Pass_Change'] = '1';
        $userModel = new Default_Model_Users();
        $user = $userModel->fetchUserId($id);
        $userModel->updateUser($id, $values);

        $config = array(
			'ssl' => 'tls',
			'port' => 587, 
			        	'auth' => 'login', 
			        	'username' => 'shaun.farrell@vacantdc.com', 
			        	'password' => 'farrell1'
			       	);
			       
    				$smtpConnection = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
    
			        $mail = new Zend_Mail();
					$mail->setBodyText("Your password has been reset.  Password: " . $password . "  You will be  asked to reset this when you first login.  If you have any questions or problems please let us know at Questions@vacantdc.com");
	        		$mail->setFrom('noreply@vacantdc.com','vacantDC');
	        		$mail->addTo($user['Email_Address'], $user['First_Name'] . ' ' . $user['Last_Name']);
	        		$mail->setSubject('vacantDC! | Password Reset');
					$mail->send($smtpConnection);
        
    }

    protected function _loginFailure($email) {

    }
    
    /**
     * Unused placeholder for success actions
     * 
     * @param $uid
     * @return void
     */
    protected function _loginSuccess($uid) {
    	
    }
}
