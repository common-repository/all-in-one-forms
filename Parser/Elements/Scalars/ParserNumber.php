<?php
namespace rednaoeasycalculationforms\Parser\Elements\Scalars;

use rednaoeasycalculationforms\Parser\Core\ParserElementBase;


class ParserNumber extends ParserElementBase{

    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);

    }

    public function Parse(){
        return \floatval($this->Data->d);
    }
}