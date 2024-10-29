<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core;


abstract class SanitizerBase
{

    public $Property;
    protected $DefaultValue;
    protected $Required;

    public function __construct($property, $defaultValue, $required=true)
    {
        $this->Property = $property;
        $this->DefaultValue = $defaultValue;
        $this->Required = $required;
    }

    public  function Sanitize($originalObject, $newObject){
        if(isset($originalObject->{$this->Property})) {
            $newObject->{$this->Property} = $this->InternalSerialize($originalObject,$newObject);
        }
        else
        {
            if($this->Required)
                $newObject->{$this->Property} = $this->DefaultValue;
        }
    }

    protected abstract function InternalSerialize($originalObject,$newObject);
}