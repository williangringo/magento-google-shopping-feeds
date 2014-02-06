<?php

class Inchoo_Gsfeed_Block_Adminhtml_Config_Edit_Tabs extends
    Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('config_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('inchoo_gsfeed')->__('Feed informations'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('inchoo_gsfeed')->__('Feed Information'),
            'title' => Mage::helper('inchoo_gsfeed')->__('Feed Information'),
            'content' => $this->getLayout()->createBlock('inchoo_gsfeed/adminhtml_config_edit_tab_form')->toHtml()
        ));

        $this->addTab('form_section2', array(
            'label' => Mage::helper('inchoo_gsfeed')->__('Feed Categories'),
            'title' => Mage::helper('inchoo_gsfeed')->__('Feed Categories'),
            'content' => $this->getLayout()
                ->createBlock('inchoo_gsfeed/adminhtml_config_edit_categories')
                ->toHtml()
        ));

        return parent::_beforeToHtml();
    }
}