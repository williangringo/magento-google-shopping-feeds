<?php

class Inchoo_Gsfeed_Block_Adminhtml_Feed extends
    Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'inchoo_gsfeed';
        $this->_controller = 'adminhtml_feed';
        $this->_headerText = $this->__('Google Shopping Feeds');

        parent::__construct();
    }
}