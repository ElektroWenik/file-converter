<?php
namespace App\Converters;

use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;
use SplTempFileObject;

class CsvConverter extends Converter {

    private $csvReader;
    private $csvWriter;

    public function __construct($content = '')
    {
        parent::__construct($content);

        if($content !== ''){
            $reader = Reader::createFromString($this->content);
            $reader->setHeaderOffset(0);
            $this->csvReader = (new Statement())->process($reader);
        }

        $this->csvWriter = Writer::createFromFileObject(new SplTempFileObject());
    }

    public function toArray(){
        return collect($this->csvReader->getRecords())->toArray();
    }
    public function fromArray($array){

        $arr = collect($array)->map(function($item){
            return collect($item)->map(function($cell){
                return gettype($cell) == 'array' ? 'array' : $cell;
            })->toArray();
        });


        $headers = $arr->reduce(function($acc, $arr){
            $acc->push(array_keys($arr));
            return $acc;
        }, collect())->collapse()->unique()->toArray();

        $this->csvWriter->insertOne($headers);
        $this->csvWriter->insertAll($arr);
        return $this->csvWriter->getContent();
    }
    public function isValid(){
        return true;
    }
}