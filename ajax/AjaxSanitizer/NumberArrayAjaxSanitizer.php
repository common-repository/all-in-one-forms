<?php

namespace rednaoeasycalculationforms\ajax\AjaxSanitizer;

use rednaoeasycalculationforms\Utilities\Sanitizer;

class NumberArrayAjaxSanitizer extends ArrayAjaxSanitizerBase
{

    protected function SanitizeItem($item)
    {
        return Sanitizer::SanitizeNumber($item,null);
    }
}