<?php
namespace App\Converters;

class JsonConverter extends Converter {

    public function toArray(){
        return json_decode($this->content, true);
    }
    public function fromArray($array){
        return json_encode($array);
    }
    public function isValid(){
        return json_decode($this->content, true) != null;
    }
}