<?php 
class Default_Model_UserComments
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
            $this->_table = new Default_Model_DbTable_UserComments();
        }
        return $this->_table;
    }
    
    /**
     * Fetch the record of an individual user by e-mail
     * 
     * @param  string $email 
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function addComment(array $data)
    {
        $fields = $this->getTable()->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset($data[$field]);
            }
        }
        return $this->getTable()->insert($data);
    }
    
    /*
     * 
     * 
     */
    public function fetchCommentsBySSL($ssl) 
    {
    	
    	$select = $this->getTable()->select()
    		->setIntegrityCheck(false)
			->from(array('m' => 'User_Comments'), array('Id', 'User_Id', 'SSL', 'Comment', 'Create_Datetime'))
			->joinInner(array('b' => 'Users'), 'b.Email_Address = m.User_Id', array('First_Name', 'Last_Name'))
			->where('m.SSL = ?',  $ssl)
			->order('m.Create_Datetime DESC');


    	$result = $this->getTable()->fetchAll($select);
      	if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
   
 }
