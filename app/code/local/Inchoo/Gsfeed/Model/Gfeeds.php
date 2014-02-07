<?php

class Inchoo_Gsfeed_Model_Gfeeds extends
    Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('feeds/gfeeds');
    }

    public function generateAll()
    {
        $feeds = $this->getCollection();
        foreach ($feeds as $feed) {
            $this->generateXML($feed->getId());
        }
    }

    /**
     * @param $feedId int
     * @return int Number of searched item
     */
    public function generateXML($feedId)
    {
        /** Fetch feed info */
        $feed = $this->load($feedId);

        /** Update DB entry */
        $feedUpdateDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
        $feed->setLastUpdate($feedUpdateDate);
        $feed->save();

        $categories = explode(',', $feed->getCategories());

        /** populate general fields */
        /** @var Inchoo_Gsfeed_Model_Extendedxml $xml */
        $xml = Mage::getModel('feeds/extendedxml');
        $xml->openURI(Mage::getBaseDir() . '/' . $feed->getLink());
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('rss');
        $xml->writeAttribute('version', '2.0');
        $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        $xml->startElement('channel');
        $xml->addCDChild('title', $feed->getTitle());
        $xml->writeElement('link', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));
        $xml->addCDChild('description', $feed->getDescription());

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
     * @return int number of searched item
     */
    public function populateItems($xml, $categories)
    {
        $_usedIds = array();

        foreach ($categories as $parentCategoryId) {

            /** In case category string started with ',' instead of id */
            if ($parentCategory = '') {
                continue;
            }

            $parentCategory = Mage::getModel('catalog/category')
                ->load($parentCategoryId);
            $childrenCategories = Mage::getModel('catalog/category')
                ->getCategories($parentCategoryId);


            if (empty($childrenCategories->getNodes())) {
                $childrenCategories[0] = $parentCategory;
                $isParent = true;
            }

            foreach($childrenCategories as $category) {
                $product = Mage::getModel('catalog/product');
                $curCategory = Mage::getModel('catalog/category')
                    ->load($category->getId());
                $collection = $curCategory
                    ->getProductCollection()
                    ->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

                /** Get configurable product quantity */
                $res = Mage::getSingleton('core/resource');
                $t1 = $res->getTableName('catalog_product_super_link');
                $t2 = $res->getTableName('cataloginventory_stock_item');
                $collection->getSelect()
                    ->joinLeft($t1 . ' AS cpsl', 'cpsl.parent_id=e.entity_id ')
                    ->joinLeft($t2 . ' AS stock', 'stock.product_id=cpsl.product_id', array('qty' => 'SUM(qty)'))
                    ->joinLeft('catalog_product_index_price AS cpip', 'cpip.entity_id=e.entity_id AND customer_group_id=0', array('front_final_price' => 'final_price'))
                    ->group(array('cpsl.parent_id'));

                $storeId = ($curCategory->getParentId() == 3) ? 2 : 1;
                foreach ($collection as $curProduct) {
                    /** Fix for placeholder image */
                    Mage::app()->setCurrentStore($storeId);

                    /** skip out of stock items */
                    $qty = $curProduct->getQty();
                    if ($qty == 0) continue;

                    /** check double products */
                    $productId = $curProduct->getID();
                    if (in_array($productId, $_usedIds)) {
                        continue;
                    } else {
                        $_usedIds[] = $productId;
                    }

                    /** load categories */
                    $product->load($productId);

                    $catIds = $product->getCategoryids();
                    $rootCategory = Mage::getModel('catalog/category')
                        ->load($catIds[0]);
                    $productType = $rootCategory->getName() .
                        ' > ' . $parentCategory->getName();
                    if (!isset($isParent)) {
                        $productType .= ' > ' . $category->getName();
                    }

                    /** get category mapping */
                    $googleMap = $rootCategory->getGoogleShoppingMapping();
                    $parentMap = $parentCategory->getGoogleShoppingMapping();
                    if ($parentMap) {
                        $googleMap .= ' > ' . $parentMap;
                    }
                    $currentMap = $curCategory->getGoogleShoppingMapping();
                    if ($currentMap && !isset($isParent)) {
                        $googleMap .= ' > ' . $currentMap;
                    }

                    /** get minimal price */
                    $finalPrice = $product->getFinalPrice();
                    $frontPrice = $curProduct->getFrontFinalPrice();
                    if ($finalPrice > $frontPrice && $frontPrice != null) {
                        $finalPrice = $frontPrice;
                    }

                    /** Write element */
                    $xml->startElement('item');
                    $xml->writeElement('g:id', $product->getSku());
                    $xml->addHEChild('title', $product->getName());

                    /** link */
                    $xml->startElement('link');
                        $xml->writeAttribute('rel', 'alternate');
                        $xml->writeAttribute('type', 'text/html');
                        $xml->writeAttribute('href',
                            Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) .
                            (($catIds[0] == 3) ? 'women/' : 'mens/') .
                            $product->getData('url_key') . '.html');
                    $xml->endElement();

                    /** price */
                    $xml->startElement('g:price');
                        $xml->writeAttribute('unit', 'GBP');
                        $xml->text(number_format($finalPrice, 2));
                    $xml->endElement();

                    $xml->writeElement('g:online_only', 'y');
                    $xml->addCDChild('description', $product->getDescription());
                    $xml->writeElement('g:condition', 'new');

                    $xml->addHEChild('g:product_type', $productType);
                    $xml->addHEChild('g:google_product_category', $googleMap);
                    $xml->writeElement('g:image_link', $product->getImageUrl());

                    /** additional_image_link */
                    $productMedia = $product->getMediaGallery();
                    foreach ($productMedia['images'] as $key => $image) {
                        if ($key != 0) {
                            $imgUrl = $product->getMediaConfig()->getMediaUrl($image['file']);
                            $xml->writeElement('g:additional_image_link', $imgUrl);
                        }
                    }

                    $xml->writeElement('g:availability', 'in stock');
                    $xml->writeElement('g:quantity', number_format($qty, 2));
                    $xml->writeElement('g:featured_product', 'no');
                    $xml->writeElement('g:color', $product->getRapidColour());// color
                    $xml->writeElement('g:gender', ($catIds[0] == 3) ? 'female' : 'male');// gender
                    $xml->writeElement('g:age_group', 'adult');

                    /** size */
                    $productAttributeOptions = $product->getTypeInstance(true)
                        ->getConfigurableAttributesAsArray($product);
                    $attributeOptions = array();
                    foreach($productAttributeOptions as $productAttribute) {
                        foreach ($productAttribute['values'] as $attribute) {
                            if ($attribute['store_label'] != '1Sze') {
                                $attributeOptions[$productAttribute['label']][$attribute['value_index']] = $attribute['store_label'];
                            }
                        }
                    }
                    if (isset($attributeOptions['Size'])) {
                        $xml->writeElement('g:size', implode($attributeOptions['Size'], '&#47;'));
                    }

                    $xml->writeElement('g:shipping_weight', '0.00 kg');
                    $xml->addCDChild('g:manufacturer', $product->getAttributeText('manufacturer'));
                    $xml->addCDChild('g:brand', $product->getAttributeText('manufacturer'));
                    $xml->writeElement('g:mpn', $product->getSku());
                    $xml->endElement(); // item
                }
            }
        }
    }
}