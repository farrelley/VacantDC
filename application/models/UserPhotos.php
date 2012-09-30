<?php 
class Default_Model_UserPhotos
{
   /** 
     * @var $_table
     */
    protected $_table;

    /**
     * Construct Method
     * @return unknown_type
     */
    public function init()
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
            $this->_table = new Default_Model_DbTable_UserPhotos();
        }
        return $this->_table;
    }
    
    public function addPhoto(array $data)
    {
        $fields = $this->getTable()->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        return $this->getTable()->insert($data);
    }
    
    public function fetchPhotosBySSL($ssl, $numRows = null)
    {
    	$select = $this->getTable()->select()
    		->where('`SSL` = ?', $ssl);
    	if($numRows !== null) {
			$select->limit( (int)$numRows, 0);
    	}
    	$select->order('Create_Datetime Desc');
    	
    	$results = $this->getTable()->fetchAll($select);
    	if(is_object($results)) {
    		return $results->toArray();
    	} else {
    		return $results;
    	}
    }

	public function fetchPhotoById($id)
    {
    	$select = $this->getTable()->select()
    		->where('Id = ?', $id);
    	$results = $this->getTable()->fetchRow($select);
    	if(is_object($results)) {
    		return $results->toArray();
    	} else {
    		return $results;
    	}
    }
    
    
}