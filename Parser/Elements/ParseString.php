<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;

class ParseString extends ParseBase
{

    public function Parse($type = null)
    {
        return strval($this->Options->d);
    }
}