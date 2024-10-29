<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseParenthesis extends ParseBase
{
    /** @var ParseBase */
    public $Sentence;
    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Sentence=ParseFactory::GetParseElement($options->d,$this);
    }


    public function Parse($type = null)
    {
        return $this->Sentence->Parse();
    }
}