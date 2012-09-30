<?php 
class Default_Model_Blog
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
            $this->_table = new Default_Model_DbTable_Blog();
        }
        return $this->_table;
    }
    
    
    public function fetchLastBlogPost()
    {
    	$select = $this->getTable()->select()
    		->where('Position = ?', 1)
    		->where('Published = ?', 1)
    		->order('date DESC')
    		->limit(0,1);
    		
        $result = $this->getTable()->fetchRow($select);
        if(is_object($result)) {
            return $result->toArray();
        } else { 
            return $result;
        }
    }
    

    
 }