<?php

class Default_Form_Comment extends Zend_Form
{
	public function __construct($options = null)
	{
		/*
		 * Form Elements
		 *   - Owner Name
		 *  
		 */
		parent::__construct($options);
		
		$this->setName('Comment');
		
		$userComment = new Zend_Form_Element_Textarea('userComment');
		$userComment->setRequired(true)
			->setAttrib('cols', 35)
			->setAttrib('rows', 15)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
						
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
			   ->setLabel('Add Comment');
		
		$this->addElements(array($userComment, $submit));
	}

}