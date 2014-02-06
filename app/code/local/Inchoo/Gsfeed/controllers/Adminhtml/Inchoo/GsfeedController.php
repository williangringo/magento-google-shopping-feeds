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
        $feedId = $this->getRequest()->getParam('id', null);
        $feed = Mage::getModel('feeds/gfeeds');

        if ($feedId != null) {
            $feed->load($feedId);

            if ($feed->getId() == false)  {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('awesome')->__('Feed does not exist'));
                $this->_redirect('*/*/');
            }
        }
        Mage::register('feed_data', $feed);

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

    public function categoriesJsonAction()
    {
        $this->getResponse()->setBody('');
    }

    public function saveAction()
    {
        $feed = Mage::getModel('feeds/gfeeds')->load(
            $this->getRequest()->getParam('id')
        );

        if ($feed->getId()) {
            $data = array(
                'feed_id' => $this->getRequest()->getParam('id'),
                'name' => $this->getRequest()->getParam('name'),
                'title' => $this->getRequest()->getParam('title'),
                'link' => $this->getRequest()->getParam('link'),
                'description' => $this->getRequest()->getParam('description'),
                'categories' => implode(',', array_unique(
                    explode(',', $this->getRequest()->getParam('category_ids')))),
            );
            $feed->setData($data);
            $feed->save();
        }

        $this->_redirect('*/*/edit', array('_current' => true));
    }

    public function deleteAction()
    {
        $feed = Mage::getModel('feeds/gfeeds')->load(
            $this->getRequest()->getParam('id')
        );
        $feed->delete();

        $this->_redirect('*/*/index');
    }
}