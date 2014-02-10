<?php

class Inchoo_Gsfeed_Block_Adminhtml_Feed_Grid extends
    Mage_Adminhtml_Block_Widget_Grid

{
    public function __construct()
    {
        parent::__construct();

        $this->setId('inchoo_gsfeed')
            ->setDefaultSort('feed_id')
            ->setUseAjax(true);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('feeds/gfeeds')
            ->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('feed_id', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Feed #'),
            'sortable' => false,
            'width' => '50px',
            'index' => 'feed_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('link', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Feed link'),
            'index' => 'link',
            'renderer' => 'Inchoo_Gsfeed_Block_Adminhtml_Feed_Link',
        ));

        $this->addColumn('updated', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Last updated'),
            'index' => 'last_update',
            'width' => '200px',
        ));

        $this->addColumn('enabled', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Status'),
            'index' => 'enabled',
            'renderer' => 'Inchoo_Gsfeed_Block_Adminhtml_Feed_Status',
            'width' => '100px',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Actions'),
            'width' => '70px',
            'type' => 'action',
            'getter' => 'getId',
            'sortable' => false,
            'actions' => array(
                array(
                    'url' => array('base' => '*/*/edit'),
                    'caption' => Mage::helper('inchoo_gsfeed')->__('Edit'),
                    'field' => 'id',
                ),
                array(
                    'url' => array('base' => '*/*/rebuild'),
                    'caption' => Mage::helper('inchoo_gsfeed')->__('Generate'),
                    'field' => 'id',
                ),
            ),
            'width' => '100px',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return Mage::helper('adminhtml')->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}