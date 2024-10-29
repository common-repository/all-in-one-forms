<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseBreak extends ParseBase{

    public function Parse($type = null)
    {
        ParseUtilities::NotifyBreakToParent($this);
    }
}