<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers;

use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class SMHTMLSanitizer extends SanitizerBase
{

    protected function InternalSerialize($originalObject, $newObject)
    {
        if(!isset($originalObject->{$this->Property}))
            return '';

        require_once AllInOneForms()->GetLoader()->DIR.'vendor/autoload.php';
        return AllInOneForms()->GetLoader()->GetHTMLSanitizer()->Sanitize($originalObject->{$this->Property});
    }

}