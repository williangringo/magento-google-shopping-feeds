<?php

class Inchoo_Gsfeed_Block_Adminhtml_Feed_Link extends
    Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $file = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $row->getData('link');

        $url = "<a href=\"{$file}\">{$file}</a>";
        return $url;
    }
}