<?php

class Inchoo_Gsfeed_Adminhtml_Inchoo_GsfeedController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
        $this->loadLayout()
            ->_setActiveMenu('catalog/inchoo_gsfeed')
            ->_addBreadcrumb($this->__('Google Shopping'), $this->__('Google Shopping'));

        return $this;
    }

    /**
     * Inital grid listing
     */
    public function indexAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()
                ->createBlock('inchoo_gsfeed/adminhtml_feed'))
            ->renderLayout();
    }

    /**
     * Generate single feed
     */
    public function rebuildAction()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            Mage::getModel('feeds/gfeeds')->generateXML($id);
        }

        $this->_redirect('*/*/index');
    }

    /**
     * New/Edit feed
     */
    public function editAction()
    {

        $this->_initAction();
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()
                ->createBlock('inchoo_gsfeed/adminhtml_config'))
            ->_addLeft($this->getLayout()
                ->createBlock('inchoo_gsfeed/adminhtml_config_edit_tabs'))
            ->renderLayout();
    }

    /**
     * Category listing
     */
    public function categoriesAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Return grid - used for ajax
     */
    public function gridAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock(
                'inchoo_gsfeed/adminhtml_feed_grid')->toHtml()
        );
    }

    public function saveAction()
    {
        var_dump($_POST);
    }
}