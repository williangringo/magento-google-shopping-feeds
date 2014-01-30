<?php

class Inchoo_Gsfeed_Block_Adminhtml_Config_Edit_Tabs extends
    Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {

        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('inchoo_gsfeed')->__('Google Shopping Feed'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('inchoo_gsfeed')->__('Item Information'),
            'title' => Mage::helper('inchoo_gsfeed')->__('Item Information'),
            'content' => $this->getLayout()
                    ->createBlock('inchoo_gsfeed/adminhtml_config_edit_tab_form')
                    ->toHtml(),
        ));
//        $this->addTab('config', array(
//            'label' => Mage::helper('inchoo_gsfeed')->__('Settings'),
//            'title' => Mage::helper('inchoo_gsfeed')->__('Edit feed settings'),
//            'content' => $this->getLayout()
//                    ->createBlock('inchoo_gsfeed/adminhtml_tabs_config_edit_form')
//                    ->toHtml(),
//        ));

//        $this->addTab('categories', array(
//            'label' => Mage::helper('inchoo_gsfeed')->__('Categories'),
//            'title' => Mage::helper('inchoo_gsfeed')->__('Select product categories'),
//            'content' => 'test2',
//            'content' => $this->getLayout()
//                    ->createBlock('inchoo_gsfeed/adminhtml_tabs_categories')
//                    ->toHtml(),
//        ));

        return parent::_beforeToHtml();
    }
}