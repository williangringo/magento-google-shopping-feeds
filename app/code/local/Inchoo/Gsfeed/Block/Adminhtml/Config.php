<?php

class Inchoo_Gsfeed_Block_Adminhtml_Config extends
    Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'inchoo_gsfeed';
        $this->_controller = 'adminhtml_tabs_config';
        $this->_mode = 'edit';

        $this->_updateButton('save', 'label', Mage::helper('inchoo_tickets')->__('Save settings'));
        $this->removeButton('reset');
    }

    public function getHeaderText()
    {
        return Mage::helper('inchoo_gsfeed')->__('Google Shopping Feed details');
    }
}