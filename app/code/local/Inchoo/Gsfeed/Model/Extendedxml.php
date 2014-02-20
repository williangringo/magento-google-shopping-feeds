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
        $pars = str_replace('&', '&amp;', $value);
        $pars = str_replace('>', '&gt;', $pars);
        $pars = str_replace('<', '&lt;', $pars);
        $this->writeElement($name, $pars);
    }

}