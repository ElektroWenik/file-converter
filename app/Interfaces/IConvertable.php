<?php

namespace App\Interfaces;

interface IConvertable {
    public function toArray();
    public function fromArray($array);
}
