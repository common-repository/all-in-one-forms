<?php

namespace rednaoeasycalculationforms\pr\Parser\Elements\Operations\Logical;

use rednaoeasycalculationforms\pr\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementBase;

class ParseCondition extends ParserElementBase{

    /** @var ParserElementBase */
    public $Comparator;
    /** @var ParserElementBase */
    public $Next;
    public $Operation;

    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);

        $this->Operation=$Data->Operation;
        $this->Comparator=ParseFactory::GetParseElement($this,$Data->Comparator);
        $this->Next=ParseFactory::GetParseElement($this,$Data->Next);

    }

    function Parse() {
        $isTrue=$this->Comparator->Parse()==true;
        if($this->Next==null)
            return $isTrue;

        $nextIsTrue=$this->Next->Parse()==true;
        if($this->Operation=="&&")
            return $isTrue&&$nextIsTrue;
        else
            return $isTrue||$nextIsTrue;


    }

}