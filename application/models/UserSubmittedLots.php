<?php 
class Default_Model_UserSubmittedLots
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
            $this->_table = new Default_Model_DbTable_UserSubmittedLots();
        }
        return $this->_table;
    }
    
    public function addLot(array $data)
    {
        $fields = $this->getTable()->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        return $this->getTable()->insert($data);
    }
    
    public function fetchLotsByUserId($userId)
    {
    	$select = $this->getTable()->select()->where('User_Id = ?', $userId);
    	$results = $this->getTable()->fetchAll($select);
    	if(is_object($results)) {
    		return $results->toArray();
    	} else {
    		return $results;
    	}
    }
    
    public function fetchUserSubmitedProperty($id, $userId)
    {
    	$select = $this->getTable()->select()
    		->where('Id = ?', $id)
    		->where('User_Id = ?', $userId);
    	
    	$result = $this->getTable()->fetchRow($select);
    	if(is_object($result)) { 
    		return $result->toArray();
    	}
    	else {
    		return $result;
    	}
    }
    public function getIdByToken($token)
    {
    	$select = $this->getTable()->select()->where('Token = ?', $token);
    	$result = $this->getTable()->fetchRow($select);
    	if(is_object($result)) 
    		return $result->toArray();
    	else
    		return $result;	
    }
    
    public function setServiceRequestId($id, array $data) 
    {
        $table  = $this->getTable();
        $fields = $table->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        $where = $table->getAdapter()->quoteInto('Id = ?', $id);
        return $table->update($data, $where);
    }
    
    public function updateServiceIdStatus($id, array $data)
    {
        $table  = $this->getTable();
        $fields = $table->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        $where = $table->getAdapter()->quoteInto('Id = ?', $id);
        return $table->update($data, $where);
    }
}