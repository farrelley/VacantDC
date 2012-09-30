<?php 
class Default_Model_Users
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
            $this->_table = new Default_Model_DbTable_Users();
        }
        return $this->_table;
    }
    
    /**
     * Fetch the record of an individual user by e-mail
     * 
     * @param  string $email 
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchUserByEmail($email)
    {
        $select = $this->getTable()->select()->where('Email_Address = ?', $email);
        $result = $this->getTable()->fetchRow($select);
        if(is_object($result)) {
            return $result->toArray();
        } else {
            return $result;
        }
    }
    
    /**
     * Add a new user
     * 
     * @param  array $data Column-value pairs.
     * @return int|string The primary key of the row inserted.
     */
    public function addUser(array $data)
    {
        $table  = $this->getTable();
        $fields = $table->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        return $table->insert($data);
    }
    
     /**
     * Fetch all users
     * 
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchUsers()
    {
        $result = $this->getTable()->fetchAll();
        if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    
	/**
     * Fetch the record of an individual user
     * 
     * @param  int|string $id 
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function fetchUser($id)
    {
        $select = $this->getTable()->select()->where('Email_Address = ?', $id);
        $result = $this->getTable()->fetchRow($select);
        if(is_object($result)) {
            return $result->toArray();
        } else {
            return $result;
        }
    }

 	public function fetchUserId($id)
    {
        $select = $this->getTable()->select()->where('Id = ?', $id);
        $result = $this->getTable()->fetchRow($select);
        echo $select;
        if(is_object($result)) {
            return $result->toArray();
        } else {
            return $result;
        }
    }
    
    /**
     * Update an existing user
     * 
     * @param int|string $id
     * @param array $data Column-value pairs.
     * @return int|string The number of rows updated.
     */
    public function updateUser($id, array $data)
    {
        $fields = $this->getTable()->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        $where = $this->getTable()->getAdapter()->quoteInto('Id = ?', $id);
        return $this->getTable()->update($data, $where);
    }
    
    

 }