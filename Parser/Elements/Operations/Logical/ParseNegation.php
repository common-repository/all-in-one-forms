<?php
namespace rednaoeasycalculationforms\pr\Parser\Elements\Operations\Logical;

use rednaoeasycalculationforms\pr\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementBase;

class ParseNegation extends ParserElementBase{
    /** @var ParserElementBase */
    public $Child;

    function __construct($Parent, $Data) {
        parent::__construct($Parent,$Data);

        $this->Child=ParseFactory::GetParseElement($this,$Data->Child);
    }

    function Parse() {
        return !$this->Child->Parse();
    }

}