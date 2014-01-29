<?php

class Inchoo_Gsfeed_Model_Resource_Gfeeds_Collection extends
    Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('feeds/gfeeds');
    }
}