<?php
namespace rednaoeasycalculationforms\pr\Parser\Elements\Math;

use rednaoeasycalculationforms\pr\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\pr\Parser\Core\ParserElementBase;

class ParseMathFunction extends ParserElementBase{

    /** @var ParserElementBase */
    public $Child;

    function __construct($Parent,$Data) {
        parent::__construct($Parent,$Data);

        if(isset($this->Data->d))
            $this->Child=ParseFactory::GetParseElement($this,$this->Data->d);
    }

    function Parse() {

        switch ($this->Data->op) {
            case 'SIN':
                return sin($this->Child->Parse());
            case 'COS':
                return cos($this->Child->Parse());
            case 'TAN':
                return tan($this->Child->Parse());
            case 'ASIN':
                return asin($this->Child->Parse());
            case 'ATAN':
                return atan($this->Child->Parse());
            case 'ACOS':
                return acos($this->Child->Parse());
            case 'SQRT':
                return sqrt($this->Child->Parse());
            case 'LN':
                return log($this->Child->Parse());
            case 'PI':
                return 3.14159265359;
            case 'E':
                return 2.718281828459045;

        }
    }

}