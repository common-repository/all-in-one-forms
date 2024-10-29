<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers;

use rednaoeasycalculationforms\core\Managers\SerializationManager\SanitizationManager;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;

class ArraySanitizer extends SanitizerBase
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
        if(!isset($originalObject->{$this->Property})||!is_array($originalObject->{$this->Property}))
        {
            if($this->Required)
                return [];
            else
                return null;
        }

        $result=[];

        foreach($originalObject->{$this->Property} as $value)
        {
            $newItem=$this->Manager->Sanitize($value);
            if($newItem!=null)
                $result[]=$newItem;
        }

        return $result;




    }
}