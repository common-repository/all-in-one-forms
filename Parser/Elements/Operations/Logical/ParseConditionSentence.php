<?php
namespace rednaoeasycalculationforms\pr\Parser\Elements\Operations\Logical;

use rednaoeasycalculationforms\pr\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementBase;

class ParseConditionSentence extends ParserElementBase{

    /** @var ParserElementBase */
    public $Condition;

    /** @var ParserElementBase */
    public $Result;
    public $IsScript;

    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);

        $this->Condition=ParseFactory::GetParseElement($this,$Data->Condition);
        $this->Result=ParseFactory::GetParseElement($this,$Data->Result);

    }

    function Parse() {
        if($this->Condition->Parse())
            return $this->Result->Parse();
        return null;
    }

}