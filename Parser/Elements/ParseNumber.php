<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class ParseNumber extends ParseBase
{


    public function Parse($type = null)
    {
        return Sanitizer::SanitizeNumber($this->Options->d);
    }
}