<?php

namespace rednaoeasycalculationforms\ajax\AjaxSanitizer;

abstract class ArrayAjaxSanitizerBase extends AjaxSanitizerBase
{
    protected $ConvertScalarToArray=false;
    public function SetConvertScalarToArray(){
        $this->ConvertScalarToArray=true;
        return $this;
    }

    protected abstract function SanitizeItem($item);

    public function Sanitize($data)
    {
        if($data==null)
            return null;

        if(!is_array($data))
        {
            if(!$this->ConvertScalarToArray)
                return null;

            $data=[$data];
        }

        $arrayToReturn=[];
        foreach ($data as $currentItem)
        {
            $item=$this->SanitizeItem($currentItem);
            if($item===null)
                return null;

            $arrayToReturn[]=$item;
        }

        return $arrayToReturn;

    }

}