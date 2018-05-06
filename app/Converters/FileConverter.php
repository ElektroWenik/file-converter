<?php
namespace App\Converters;

use Illuminate\Support\Facades\Storage;

class FileConverter {

    private $source;
    private $output;
    private $convertedContent;

    function __construct(Converter $source, Converter $output) {
        $this->source = $source;
        $this->output = $output;
        $this->convertedContent = false;
    }
    public function convert(){
        if($this->source->isValid()) {
            $this->convertedContent = $this->output->fromArray($this->source->toArray());
            return true;
        }
        return false;
    }

    public function getFile($fileName){

        Storage::disk('public')->put($fileName, $this->convertedContent);
        return Storage::url($fileName);
    }

}