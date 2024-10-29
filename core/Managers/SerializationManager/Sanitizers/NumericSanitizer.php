<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers;

use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class NumericSanitizer extends SanitizerBase
{

    protected function InternalSerialize($originalObject, $newObject)
    {
        return Sanitizer::SanitizeNumber($originalObject->{$this->Property},$this->DefaultValue);
    }
}