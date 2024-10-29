<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParserElementBase;

class ParseSentence extends ParserElementBase{

    /** @var ParserElementBase */
    public $Sentence;
    /** @var ParserElementBase */
    public $Next;

    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);

        $this->Sentence=ParseFactory::GetParseElement($this,$this->Data->Sentence);
        if(isset($this->Data->Next))
            $this->Next=ParseFactory::GetParseElement($this,$this->Data->Next);
    }

    function Parse() {
        return $this->Sentence->Parse();
    }
}