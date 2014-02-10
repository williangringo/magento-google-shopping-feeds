<?php

class Inchoo_Gsfeed_Block_Adminhtml_Feed_Status extends
    Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $enabled = $row->getData('enabled');

        if ($enabled) {
            $status = '<span class="grid-severity-notice"><span>Enabled</span></span>';
        } else {
            $status = '<span class="grid-severity-critical"><span>Disabled</span></span>';
        }

        return $status;
    }
}