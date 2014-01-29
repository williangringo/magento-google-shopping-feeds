<?php

class Inchoo_Gsfeed_Block_Adminhtml_Tabs_Config extends
    Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'gsfeed_config';
        $this->_blockGroup = 'inchoo_gsfeed';
        $this->_controller = 'adminhtml_tabs_config';
        $this->_mode = 'edit';

        $this->_updateButton('save', 'label', Mage::helper('inchoo_tickets')->__('Submit reply'));
        $this->removeButton('reset');
        $this->removeButton('delete');
    }

    public function getHeaderText()
    {
        return Mage::helper('inchoo_tickets')->__('Submit reply');
    }
}