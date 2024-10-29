<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;

class ParseBool extends ParseBase
{
    public function Parse($type = null)
    {
        return $this->Options->d;
    }
}