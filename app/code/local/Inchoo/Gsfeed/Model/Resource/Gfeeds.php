<?php

class Inchoo_Gsfeed_Model_Resource_Gfeeds extends
    Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('feeds/gfeeds', 'feed_id');
    }
}