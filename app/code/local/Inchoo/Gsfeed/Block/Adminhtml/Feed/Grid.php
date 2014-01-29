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
            ->getcollection();
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

        $this->addColumn('updated', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Last updated'),
            'index' => 'last_update',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('inchoo_gsfeed')->__('Actions'),
            'width' => '70px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'url' => array('base' => '*/*/edit'),
                    'caption' => Mage::helper('inchoo_gsfeed')->__('Edit'),
                    'field' => 'id',
                ),
                array(
                    'url' => array('base' => '*/*/generate'),
                    'caption' => Mage::helper('inchoo_gsfeed')->__('Generate'),
                    'field' => 'id',
                ),
                array(
                    'url' => array('base' => '*/*/delete'),
                    'caption' => Mage::helper('inchoo_gsfeed')->__('Delete'),
                    'field' => 'id',
                ),
            )
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