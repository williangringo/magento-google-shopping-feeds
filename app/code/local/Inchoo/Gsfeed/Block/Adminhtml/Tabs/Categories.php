<?php

class Inchoo_Gsfeed_Block_Adminhtml_Tabs_Categories extends
    Mage_Adminhtml_Block_Catalog_Category_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('inchoo/gsfeed/categories.phtml');
    }
}