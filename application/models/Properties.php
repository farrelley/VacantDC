<?php 
class Default_Model_Properties
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
            $this->_table = new Default_Model_DbTable_Properties();
        }
        return $this->_table;
    }
    
    public function fetchMapData()
    {
    	$select = $this->getTable()->select()
    		->from($this->_table, array('Square', 'Suffix', 'Lot', 'Latitude', 'Longitude'));
    		
        $result = $this->getTable()->fetchAll($select);
        if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    /**
     * Fetch all users
     * 
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchZipcodes()
    {
    	$select = $this->getTable()->select('Zipcode')
    		->distinct()
    		->from($this->_table, 'Zipcode')
    		->where('Zipcode is not null')
    		->order('Zipcode ASC');
    		
        $result = $this->getTable()->fetchAll($select);
        if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchQuadrants()
    {
    	$select = $this->getTable()->select('Quadrant')
    		->distinct()
    		->from($this->_table, 'Quadrant')
    		->where('Quadrant is not null')
    		->order('Quadrant ASC');
    		
    	$result = $this->getTable()->fetchAll($select);
     	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    	
    }
    
    
    public function fetchSslByAddress($addressNumber, $street, $streetType, $quadrant)
    {
    	$select = $this->getTable()->select();
    	
    	if($addressNumber !== "") {
    		$select
    			->where($this->_table->getAdapter()->quoteInto('Address_Number = ?', $addressNumber));
    	}
    	
    	$select
    		->where($this->_table->getAdapter()->quoteInto('Street_Name like ?', $street))
    		->where($this->_table->getAdapter()->quoteInto('Street_Type = ?', $streetType))
    		->where($this->_table->getAdapter()->quoteInto('Quadrant = ?', $quadrant));

    	$result = $this->getTable()->fetchAll($select);
    	
		if(is_object($result)) {
			return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchAddressBySSL(array $ssl)
    {
    	$select = $this->getTable()->select()
    		->from($this->_table, array('Address_Number', 'Street_Name', 'Street_Type', 'Quadrant'))
    		->where('Square = ?', $ssl['square'])
    		->where('Suffix = ?', $ssl['suffix'])
    		->where('Lot = ?', $ssl['lot']);
    	
    	$result = $this->getTable()->fetchRow($select);
    	if(is_object($result)) {
			return $result->toArray();
        } else { 
            return $result;
        }
    }
    
    public function fetchStreetTypes() 
    {
    	$select = $this->getTable()->select()
    		->distinct()
    		->from($this->_table, array('key' => 'Street_Type', 'value' => 'Street_Type'))
    		->where('Street_Type is not null')
    		->order('Street_Type ASC');
    	
    	$result = $this->getTable()->fetchAll($select);
		if(is_object($result)) {
			return $result->toArray();
        } else { 
            return $result;
        }
    }
         
}