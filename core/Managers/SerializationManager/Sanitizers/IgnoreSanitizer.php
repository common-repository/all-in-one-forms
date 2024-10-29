<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers;

use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;

class IgnoreSanitizer extends SanitizerBase
{

    protected function InternalSerialize($originalObject, $newObject)
    {
        if(isset($originalObject->{$this->Property}))
            return $originalObject->{$this->Property};

        return $this->DefaultValue;
    }
}