<?php

namespace App\Converters;

use App\Interfaces\IConvertable;
use App\Interfaces\IValidatable;

abstract class Converter implements IConvertable, IValidatable {

    protected $content;

    function __construct($content = '') {
        $this->content = $content;
    }
    static public function createFromFile($file){
        $converter = get_called_class();
        return new $converter(file_get_contents($file));
    }
}