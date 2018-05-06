<?php
namespace App\Converters;

use Symfony\Component\Yaml\Yaml;

class YmlConverter extends Converter {
    public function toArray(){
        return Yaml::parse($this->content);
    }
    public function fromArray($array){
        return Yaml::dump($array);
    }
    public function isValid(){
        try{
            Yaml::parse($this->content);
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }
}

