<?php

class Inchoo_Gsfeed_Block_Adminhtml_Tabs extends
    Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        $this->setId('inchoo_gsfeed');
        $this->setTitle(Mage::helper('inchoo_gsfeed')->__('Google Shopping Feed'));
        parent::__construct();
    }

    protected function _beforeToHtml()
    {
        $this->addTab('config', array(
            'label' => Mage::helper('inchoo_gsfeed')->__('Settings'),
            'title' => Mage::helper('inchoo_gsfeed')->__('Edit feed settings'),
            'content' => $this->getLayout()->createBlock('inchoo_gsfeed/adminhtml_tabs_config_edit_form')->toHtml(),
        ));

        $this->addTab('categories', array(
            'label' => Mage::helper('inchoo_gsfeed')->__('Categories'),
            'title' => Mage::helper('inchoo_gsfeed')->__('Select product categories'),
            'class' => 'ajax',
            'url' => $this->getUrl('*/*/categories', array('_current' => true)),
            'active' => false,

        ));

        return parent::_beforeToHtml();
    }
}