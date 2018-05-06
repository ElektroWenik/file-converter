<?php
namespace App\Converters;

use DOMDocument;
use LSS\XML2Array;
use App\Helpers\XmlHelper;
use SimpleXMLElement;

class XmlConverter extends Converter {
    public function toArray(){
        return XML2Array::createArray($this->content);
    }
    public function fromArray($array){
        $content = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><content></content>');
        XmlHelper::ArrayToXml($array, $content);

        return $content->asXML();
    }
    public function isValid(){
        libxml_use_internal_errors(true);
        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->loadXML($this->content);
        $errors = libxml_get_errors();
        return empty($errors);
    }
}

