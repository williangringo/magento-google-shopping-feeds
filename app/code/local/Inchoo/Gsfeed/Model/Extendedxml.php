<?php

class Inchoo_Gsfeed_Model_Extendedxml extends XMLWriter
{
    protected function _construct()
    {
        $this->_init('feeds/extendedxml');
    }

    public function addChild($name, $value = null)
    {
        $this->startElement($name);
        $this->writeCdata($value);
        $this->endElement();

    }
}