<?php

class Inchoo_Gsfeed_Model_Gfeeds extends
    Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('feeds/gfeeds');
    }
}