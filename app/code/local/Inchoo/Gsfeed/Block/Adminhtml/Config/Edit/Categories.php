<?php

class Inchoo_Gsfeed_Block_Adminhtml_Config_Edit_Categories extends
    Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    protected $_categoryIds;
    protected $_selectedNodes = null;

    public function __construct() {
        parent::__construct();
        $this->setTemplate('inchoo/test/categories.phtml');
    }

    protected function getCategoryIds() {
        return array();
    }

    public function isReadonly() {
        return false;
    }

    public function getIdsString() {
        return implode(',', $this->getCategoryIds());
    }
}