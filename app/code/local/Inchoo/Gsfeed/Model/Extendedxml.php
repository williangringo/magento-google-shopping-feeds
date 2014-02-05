<?php

class Inchoo_Gsfeed_Model_Extendedxml extends XMLWriter
{
    protected function _construct()
    {
        $this->_init('feeds/extendedxml');
    }

    public function addCDChild($name, $value = null)
    {
        $this->startElement($name);
        $this->writeCdata($value);
        $this->endElement();

    }

    public function addHEChild($name, $value = null)
    {
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_XML1);
        $this->writeElement($name, $value);
    }

}