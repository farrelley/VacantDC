<?php

class Default_Form_Account_Changepass extends Zend_Form
{
	public function init()
	{

		$this->setName('changepass')
			 ->setMethod('post')
			 ->addElementPrefixPath('Apps09_Validate', 'Apps09/Validate/', 'validate');
       	 
			 
		$currentPassword = new Zend_Form_Element_Password('Current_Password');
		$currentPassword->setLabel('Current Password:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

		$newPassword = new Zend_Form_Element_Password('New_Password');
		$newPassword->setLabel('New Password:')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty')
			->addValidator('PasswordStrength', true, array('minLength' => '8', 'minUpper' => '1', 'minLower' => '1', 'minDigits' => '1', 'minSpecial' => '0'))
      		->addValidator('IdenticalField', true, array('Confirm_Password', 'Confirm Password'));
           
		$confirmPassword = new Zend_Form_Element_Password('Confirm_Password');
		$confirmPassword->setLabel('Password (confirm):')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
			   ->setLabel('Change Password');
		
		$this->addElements(array($currentPassword, $newPassword, $confirmPassword, $submit));

	}
}