<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class ParseFixed extends ParseBase
{
    public $Id;
    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Id = $options->Id;
    }


    public function Parse($type = null)
    {
        return $this->GetRetriever()->GetFixedValue($this->Id,$this->Options);
    }
}