<?php
// application/models/DbTable/Guestbook.php

/**
 * This is the DbTable class for the guestbook table.
 */
class Default_Model_DbTable_UserSubmittedLots extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'User_Submitted_Lots';
    protected $_primary = 'Id';
}