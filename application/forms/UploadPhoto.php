<?php

class Default_Form_UploadPhoto extends Zend_Form
{
	public function __construct($propertyId, $options = null)
	{
		parent::__construct($options);
	
		$this->setName('photo');
		$this->setAttrib('enctype', 'multipart/form-data');
		
		$id = new Zend_Form_Element_Hidden('id');
		$id->setValue($propertyId);
					
		$photo = new Zend_Form_Element_File('photo');
		$photo->setLabel('Upload an image:');
		
		// ensure only 1 file
		$photo->addValidator('Count', false, 1);
		// limit to 3MB
		$photo->addValidator('Size', true, 3145728);
		// only JPEG, PNG, and GIFs
		$photo->addValidator('Extension', false, 'jpg,png,gif');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		
		$this->addElements(array($id, $photo, $submit));
	}

}