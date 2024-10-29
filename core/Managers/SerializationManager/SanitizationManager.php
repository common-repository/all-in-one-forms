<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager;

use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\ArraySanitizer;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\IgnoreSanitizer;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\NumericSanitizer;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\ObjectSanitizer;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\SMHTMLSanitizer;
use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\StringSanitizer;

class SanitizationManager
{
    /**
     * @var SanitizerBase[]
     */
    private $Sanitizers=[];
    public $CreateOnNull=false;

    public function SetDontCreateOnNull()
    {
        $this->CreateOnNull=false;
        return $this;
    }

    /**
     * @var SanitizerBase[]
     */
    public function AddSanitizer($sanitizer)
    {
        $this->Sanitizers[]=$sanitizer;
    }

    public function AddStringSanitizer($property, $defaultValue='', $required=true)
    {
        $item=new StringSanitizer($property, $defaultValue, $required);
        $this->Sanitizers[]=$item;
    }

    public function AddHTMLSanitizer($property, $defaultValue='', $required=true)
    {
        $item=new SMHTMLSanitizer($property, $defaultValue, $required);
        $this->Sanitizers[]=$item;
    }

    public function RemoveSanitizer($property)
    {
        for($i=0;$i<count($this->Sanitizers);$i++)
        {
            $currentSanitize=$this->Sanitizers[$i];
            if($currentSanitize->Property==$property)
            {
                unset($this->Sanitizers[$i]);
                $this->Sanitizers=array_values($this->Sanitizers);
            }
        }
    }
    public function AddNumericSanitizer($property, $defaultValue=0, $required=true)
    {
        $item=new NumericSanitizer($property, $defaultValue, $required);
        $this->Sanitizers[]=$item;
    }

    public function Sanitize($object)
    {
        if($object==null&&!$this->CreateOnNull)
            return null;

        $result=(Object)[];

        foreach($this->Sanitizers as $ObjectToSanitize)
        {
            $ObjectToSanitize->Sanitize($object, $result);
        }

        if(count(get_object_vars($result))==0&&!$this->CreateOnNull)
            return null;

        return $result;


    }

    public function AddArraySanitizer($property,$default=[],$required=true)
    {
        $array=new ArraySanitizer($property,$default,$required);
        $this->Sanitizers[]=$array;
        return $array;
    }

    public function AddIgnoreSanitizer($property,$defaultValue,$required=true)
    {
        $object=new IgnoreSanitizer($property,$defaultValue,$required);
        $this->Sanitizers[]=$object;
        return $object;
    }

    public function AddObjectSanitizer($property,$default=[],$required=true)
    {
        $array=new ObjectSanitizer($property,$default,$required);
        $this->Sanitizers[]=$array;
        return $array;
    }


    public function GetSanitizerByProperty($property)
    {
        foreach($this->Sanitizers as $currentSanitizer)
        {
            if($currentSanitizer->Property==$property)
                return $currentSanitizer;
        }
        return null;

    }
}