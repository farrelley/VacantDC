<?php
// application/models/DbTable/Guestbook.php

/**
 * This is the DbTable class for the guestbook table.
 */
class Default_Model_DbTable_Blog extends Zend_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'blog_articles';
    protected $_primary = 'id';
}