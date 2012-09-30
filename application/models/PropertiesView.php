<?php 
class Default_Model_PropertiesView
{
   /** 
     * @var $_table
     */
    protected $_table;

    /**
     * Construct Method
     * @return unknown_type
     */
    public function __construct()
    {

    }

    /**
     * Retrieve table object
     * 
     * @return Model_People_Table
     */
    public function getTable()
    {
        if (null === $this->_table) {
            $this->_table = new Default_Model_DbTable_PropertiesView();
        }
        return $this->_table;
    }
    
    /**
     * Fetch all users
     * 
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchProperties()
    {
    	$select = $this->getTable()->select()
    		->where('Address_Number is not Null')
    		->order(array('Street_Name ASC', 'Address_Number ASC'));
    		
        $result = $this->getTable()->fetchAll($select);
        if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchProperty($square, $suffix, $lot)
    {
    	$select = $this->getTable()->select()
    		->where($this->_table->getAdapter()->quoteInto('Square = ?', $square))
    		->where($this->_table->getAdapter()->quoteInto('Suffix = ?', $suffix))
    		->where($this->_table->getAdapter()->quoteInto('Lot = ?', $lot));
    	
    	$result = $this->getTable()->fetchRow($select);
      	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
	public function fetchWards()
    {
    	$select = $this->getTable()->select('Ward')
    		->distinct()
    		->from($this->_table, 'Ward')
    		->where('Ward is not null')
    		->order('Ward ASC');
    		
    	$result = $this->getTable()->fetchAll($select);
     	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchPropertiesByWard($ward) 
    {
    	$select = $this->getTable()->select()
    		->where('Ward = ?', $ward);
    	
    	$result = $this->getTable()->fetchAll($select);
      	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }

    public function fetchPropertiesByZipcode($zipcode) 
    {
    	$select = $this->getTable()->select()
    		->where('Zipcode = ?', $zipcode);
    	
    	$result = $this->getTable()->fetchAll($select);
      	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchPropertiesByQuadrant($quadrant) 
    {
    	$select = $this->getTable()->select()
    		->where('Quadrant = ?', $quadrant);
    	
    	$result = $this->getTable()->fetchAll($select);
      	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchPropertiesByOwner($owner, $id) 
    {
    	$select = $this->getTable()->select()
    		->from($this->_table,
                    array('Square', 'Suffix', 'Lot', 'Address_Number', 'Street_Name', 'Street_Type', 'Quadrant', 'Zipcode'))
    		->where('Owner_Name = ?', $owner)
    		->where('Id != ?', $id);
    		//->where('Square != ?', $square)
    		//->where('Suffix != ?', $suffix)
    		//->where('Lot != ?', $lot);
    	
    	$results = $this->getTable()->fetchAll($select);
    	if(is_object($results)) {
            return $results->toArray();
        } else { 
            return $results;
        }
    }
    
}