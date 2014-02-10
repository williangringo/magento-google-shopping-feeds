<?php

class Inchoo_Gsfeed_Model_Observer {
    public function generate() {
        Mage::getModel('feeds/gfeeds')->generateAll();
    }
}