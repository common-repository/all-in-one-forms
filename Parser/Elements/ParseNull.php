<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;

class ParseNull extends ParseBase
{

    public function Parse($type = null)
    {
        return null;
    }
}