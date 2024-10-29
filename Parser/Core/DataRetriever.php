<?php

namespace rednaoeasycalculationforms\Parser\Core;

use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class DataRetriever
{
    static $GlobalFunctions=[];
    public $VarDictionary=[];
    public $FunctionDictionary=[];
    /** @var FormBuilder */
    public $FormBuilder;
    public $Attributes=[];
    /** @var \Closure */
    public $CustomRetriever;

    public function __construct($formBuilder, $customRetriever=null)
    {
        $this->CustomRetriever=$customRetriever;
        $this->FunctionDictionary=self::$GlobalFunctions;
        $this->FormBuilder=$formBuilder;
    }

    public function GetFieldById($fieldId)
    {
        return $this->FormBuilder->GetFieldById($fieldId);
    }

    public function SetVar($varName,$value)
    {
        $this->VarDictionary[$varName]=$value;
    }

    public function &GetVar($varName)
    {
        if(isset($this->VarDictionary[$varName]))
            return $this->VarDictionary[$varName];
        $null=null;
        return $null;
    }

    public function AddFunction($name,$callBack)
    {
        $this->FunctionDictionary[$name]=$callBack;
    }

    public function CallFunction($name,$args)
    {
        if(isset($this->FunctionDictionary[$name]))
        {
            $callBack=$this->FunctionDictionary[$name];
            return $callBack($this,...$args);
        }
        return null;
    }

    public function GetAttribute($name)
    {
        if(isset($this->Attributes[$name]))
            return $this->Attributes[$name];
        return '';
    }

    public function AddAttribute($name,$value)
    {
        $this->Attributes[$name]=$value;
    }

    public function GetFixedValue($id,$options)
    {
        if($this->CustomRetriever!=null) {
            $value= call_user_func($this->CustomRetriever, $this, $id, $options);
            if($value!==null)
                return $value;
        }

        $subId=Sanitizer::GetStringValueFromPath($options,['Op','Id']);
        return $this->GetAttribute($id.($subId==''?'':'_'.$subId));
    }


}