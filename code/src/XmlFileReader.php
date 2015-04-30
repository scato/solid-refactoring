<?php

class XmlFileReader implements DataReaderInterface
{
    private $dom;
    private $nextUser;

    public function __construct()
    {
        $this->dom = new DOMDocument();
        $this->dom->load('users.xml');
        $this->nextUser = 0;
        $this->numUsers = $this->dom->documentElement->childNodes->length;
    }

    public function readData()
    {
        // TODO: implement
        return array();
    }
}
