<?php

class Inchoo_Gsfeed_Adminhtml_Inchoo_GsfeedController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('catalog/inchoo_gsfeed')
            ->_addBreadcrumb($this->__('Google Shopping'), $this->__('Google Shopping'));

        return $this;
    }

    public function indexAction()
    {
//        $this->_initAction()
//            ->_addContent($this->getLayout()
//                ->createBlock('inchoo_gsfeed/adminhtml_feed'))
//            ->renderLayout();
//        $this->getResponse()->setHeader('Content-type', 'text/xml', true);
        Mage::getModel('feeds/gfeeds')->generateXML(1);
    }

    public function editAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()
                ->createBlock('inchoo_gsfeed/adminhtml_config'))
            ->_addLeft($this->getLayout()
                ->createBlock('inchoo_gsfeed/adminhtml_config_edit_tabs'))
            ->renderLayout();
    }

    public function categoriesAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock(
                'inchoo_gsfeed/adminhtml_feed_grid')->toHtml()
        );
    }
}