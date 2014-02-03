<?php

class Inchoo_Gsfeed_Model_Gfeeds extends
    Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('feeds/gfeeds');
    }

    public function generateXML($feedId)
    {
        /** Fetch feed info */
        $feed = $this->load($feedId);
        $categories = explode(',', $feed->getCategories());

        /** populate general fields */
        $xml = Mage::getModel('feeds/extendedxml');
        $xml->openURI(Mage::getBaseDir() . '/' . $feed->getLink());
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('rss');
        $xml->writeAttribute('version', '2.0');
        $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        $xml->startElement('channel');
        $xml->writeElement('title', $feed->getTitle());
        $xml->writeElement('link', Mage::getBaseUrl() . $feed->getLink());
        $xml->writeElement('description', $feed->getDescription());

        $this->populateItems($xml, $categories);

        $xml->endElement(); // channel
        $xml->endElement(); // rss
        $xml->endDocument();
        $xml->flush();
    }

    /**
     * Populate parent with items
     *
     * @param $xml Inchoo_Gsfeed_Model_Extendedxml
     * @param $categories array
     */
    public function populateItems($xml, $categories)
    {
        foreach ($categories as $catId) {
            $cat = Mage::getModel('catalog/category')->load($catId);
            $product = Mage::getModel('catalog/product');

            $collection = $cat->getProductCollection()
                ->addFieldToFilter('status',Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

            /** Get configurable product quantity */
            $res = Mage::getSingleton('core/resource');
            $t1 = $res->getTableName('catalog_product_super_link');
            $t2 = $res->getTableName('cataloginventory_stock_item');
            $collection->getSelect()->joinLeft($t1 . ' AS cpsl', 'cpsl.parent_id=e.entity_id ');
            $collection->getSelect()->joinLeft($t2 . ' AS stock', 'stock.product_id=cpsl.product_id', array('qty' => 'SUM(qty)'));
            $collection->getSelect()->group(array('cpsl.parent_id'));

            foreach ($collection as $curProduct) {
                $qty = $curProduct->getQty();
                if ($qty == 0) continue; // skip out of stock items

                $product->load($curProduct->getID());
                $catIds = $product->getCategoryids();
                $catParent = Mage::getModel('catalog/category')->load($catIds[1]);
                $categoryMapping = $catParent->getGoogleShoppingMapping();

                if ($categoryMapping == null) continue;

                if (isset($catIds[2])) {
                    $catChild = Mage::getModel('catalog/category')->load($catIds[2]);
                }

                $xml->startElement('item');
                $xml->addChild('g:id', $product->getId());
                $xml->addChild('title', $product->getName());
                $xml->addChild('link', $product->getProductUrl());
                $xml->startElement('g:price');
                    $xml->writeAttribute('unit', 'GBP');
                    $xml->writeCdata($product->getFinalPrice()); // gbp
                    $xml->endElement();
                $xml->addChild('g:online_only', 'y');
                $xml->addChild('description', $product->getDescription());
                $xml->addChild('g:condition', 'new');
                $xml->addChild('g:product_type', $catParent->getName());
                if (isset($catChild)) {
                    $xml->addChild('g:product_type', $catParent->getName() . ' > ' . $catChild->getName());
                }
                $xml->addChild('g:google_product_category', '');
                $xml->addChild('g:image_link', $product->getImageUrl());
                // additional_image_link -- sve slike
                $xml->addChild('g:availability', 'in stock');
                $xml->addChild('g:quantity', $qty);
                $xml->addChild('g:featured_product', 'no');
                $xml->addChild('g:color', $product->getRapidColor());// color
                $xml->addChild('g:gender', ($catIds[0] == 3) ? 'female' : 'male');// gender
                $xml->addChild('g:age_group', 'adult');

                // size
                $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
                $attributeOptions = array();
                foreach($productAttributeOptions as $productAttribute) {
                    foreach ($productAttribute['values'] as $attribute) {
                        if ($attribute['store_label'] != '1Sze') {
                            $attributeOptions[$productAttribute['label']][$attribute['value_index']] = $attribute['store_label'];
                        }
                    }
                }
                if (isset($attributeOptions['Size'])) {
                    $xml->addChild('g:size', implode($attributeOptions['Size'], '&#47;'));
                }

                $xml->addChild('g:shipping_weight', '0.00 kg');
                $xml->addChild('g:manufacturer', $product->getAttributeText('manufacturer'));
                $xml->addChild('g:brand', $product->getAttributeText('manufacturer'));
                $xml->addChild('g:mpn', $product->getSku());// mpn
                $xml->endElement(); // item
            }
        }
    }
}