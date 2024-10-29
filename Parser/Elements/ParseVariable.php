<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;

class ParseVariable extends ParseBase
{
    public $Name;
    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Name=$options->d;
    }


    public function &Parse($type = null)
    {
        return $this->GetRetriever()->GetVar($this->Name);
    }

    public function Assign($value)
    {
        $this->GetRetriever()->SetVar($this->Name,$value);
    }
}