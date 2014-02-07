<?php

class Inchoo_Gsfeed_Block_Adminhtml_Config_Edit_Categories extends
    Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    protected $_categoryIds;
    protected $_selectedNodes = null;

    /**
     * Set file template
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('inchoo/test/categories.phtml');
    }

    /**
     * @return array Array of selected category ids
     */
    protected function getCategoryIds() {
        return explode(',',Mage::registry('feed_data')->getCategories());
    }

    /**
     * @return bool
     */
    public function isReadonly() {
        return false;
    }

    /**
     * @return string Convert cateogry ids array to string
     */
    public function getIdsString() {
        return implode(',', $this->getCategoryIds());
    }

    /**
     * Return root tree node
     *
     * @param null $parentNodeCategory
     * @param int $recursionLevel
     * @return mixed|Varien_Data_Tree_Node
     */
    public function getRoot($parentNodeCategory = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeCategory) && $parentNodeCategory->getId()) {
            return $this->getNode($parentNodeCategory, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $storeId = (int) $this->getRequest()->getParam('store');

            if ($storeId) {
                $store = Mage::app()->getStore($storeId);
                $rootId = $store->getRootCategoryId();
            }
            else {
                $rootId = Mage_Catalog_Model_Category::TREE_ROOT_ID;
            }

            $ids = array();
            $tree = Mage::getResourceSingleton('catalog/category_tree')
                ->loadByIds($ids, false, false);

            if ($this->getCategory()) {
                $tree->loadEnsuredNodes($this->getCategory(), $tree->getNodeById($rootId));
            }

            $tree->addCollectionData($this->getCategoryCollection());

            $root = $tree->getNodeById($rootId);

            if ($root && $rootId != Mage_Catalog_Model_Category::TREE_ROOT_ID) {
                $root->setIsVisible(true);
                if ($this->isReadonly()) {
                    $root->setDisabled(true);
                }
            }
            elseif($root && $root->getId() == Mage_Catalog_Model_Category::TREE_ROOT_ID) {
                $root->setName(Mage::helper('catalog')->__('Root'));
            }

            Mage::register('root', $root);
        }

        return $root;
    }
}