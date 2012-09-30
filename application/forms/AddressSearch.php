<?php

class Default_Form_AddressSearch extends Zend_Form
{
	public function __construct($options = null)
	{
		
		/*
		 * Form Elements
		 *  - Address Number
		 *  - Street Name
		 *  - Street Type
		 *  - Quadrant
		 *  
		 */
		parent::__construct($options);
		
		
		$this->setName('AddressSearch');
		
		$addressNumber = new Zend_Form_Element_Text('addressNumber');
		$addressNumber->setLabel('Number')
			->setRequired(false)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
			
		$streetName = new Zend_Form_Element_Text('streetName');
		$streetName->setLabel('Street Name')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');

		$streetType = new Zend_Form_Element_Select('streetType');
		$streetType->setLabel('Street Type')
			->setRequired(true)
			->setMultiOptions($this->getStreetTypes())
			->addValidator('NotEmpty');
			
		$quadrant = new Zend_Form_Element_Select('quadrant');
		$quadrant->setLabel('Quadrant')
			->setMultiOptions(array('SE'=>'SE', 'SW'=>'SW', 'NE' => 'NE', 'NW' => 'NW'))
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty');
			
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
			   ->setLabel('Go');
		
		$this->addElements(array($addressNumber, $streetName, $streetType, $quadrant, $submit));
	}
	
	private function getStreetTypes() {
		$properties = new Default_Model_Properties();
		$streetTypes = $properties->fetchStreetTypes();
		
		
		return $streetTypes;
	}
}