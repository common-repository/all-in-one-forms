<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers;

use rednaoeasycalculationforms\core\Managers\SerializationManager\SanitizationManager;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;

class ObjectSanitizer extends SanitizerBase
{
    /** @var SanitizationManager */
    public $Manager;
    public function __construct($property, $defaultValue, $required = true)
    {
        parent::__construct($property, $defaultValue, $required);
        $this->Manager=new SanitizationManager();
    }

    protected function InternalSerialize($originalObject, $newObject)
    {
        if(!isset($originalObject->{$this->Property})||!is_object($originalObject->{$this->Property}))
        {
            if($this->Required)
                return (Object)[];
            else
                return null;
        }

        return $this->Manager->Sanitize($originalObject->{$this->Property});
    }
}