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
        $xml = new Inchoo_Gsfeed_Model_Extendedxml();
        $xml->openURI(Mage::getBaseDir() . '/feed.xml');
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('rss');
        $xml->writeAttribute('version', '2.0');
        $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        //TODO: Write namespace
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

            foreach ($cat->getProductCollection() as $curProduct) {
                $product->load($curProduct->getID());
                $catIds = $product->getCategoryids();
                $catParent = Mage::getModel('catalog/category')->load($catIds[0])->getName();
                if (isset($catIds[1])) {
                    $catChild = Mage::getModel('catalog/category')->load($catIds[1])->getName();
                }
                $xml->startElement('item');

                $xml->addChild('g:id', $product->getId());
                $xml->addChild('title', $product->getName());
                $xml->addChild('link', $product->getProductUrl());
                $xml->addChild('g:price', $product->getFinalPrice());
                $xml->addChild('g:online_only', 'y');
                $xml->addChild('description', $product->getDescription());
                $xml->addChild('g:condition', 'new');
                $xml->addChild('g:product_type', $catParent);
                if (isset($catChild)) {
                    $xml->addChild('g:product_type', $catParent . ' > ' . $catChild);
                }
                // google_product_category
                $xml->addChild('g:image_link', $product->getImageUrl());
                // additional_image_link -- sve slike
                $xml->addChild('g:availability', 'in stock');
                // quantity
                $xml->addChild('g:featured_product', 'no');
                // color
                // gender
                $xml->addChild('g:age_group', 'adult');
                // size -- S/M/L/XL/XXL
                $xml->addChild('g:shipping_weight', '0.00 kilograms');
                $xml->addChild('g:manufacturer', $product->getAttributeText('manufacturer'));
                $xml->addChild('g:brand', $product->getAttributeText('manufacturer'));
                $xml->addChild('g:mpn', $product->getSku());// mpn
                $xml->endElement(); // item
            }
        }
    }
}