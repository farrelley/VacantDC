<?php

class Default_Form_OwnerSearch extends Zend_Form
{
	public function __construct($options = null)
	{
		/*
		 * Form Elements
		 *   - Owner Name
		 *  
		 */
		parent::__construct($options);
		
		$this->setName('OwnerSearch');
		
		$ownerName = new Zend_Form_Element_Text('ownerName');
		$ownerName->setLabel('Owner Name')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
						
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
			   ->setLabel('Go');
		
		$this->addElements(array($ownerName, $submit));
	}

}