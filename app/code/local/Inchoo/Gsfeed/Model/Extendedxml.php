<?php

class Inchoo_Gsfeed_Model_Extendedxml extends XMLWriter
{
    public function addChild($name, $value = null, $namespace = null)
    {
        $this->startElement($name);
        $this->writeCdata(htmlspecialchars($value, ENT_QUOTES | ENT_XML1));
        $this->endElement();

    }
}