<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;



use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBNameField extends FBFieldWithPrice
{
    public function GetLineItems()
    {
        $item= parent::GetLineItems()[0];

        $format=$this->Entry->Value->Format;
        if($format=='first_and_last')
        {
            $item->Value=$this->Entry->Value->FirstName.' '.$item->Value->LastName;
            $item->ExValue1=$this->Entry->Value->FirstName;
            $item->ExValue2=$this->Entry->Value->LastName;
            $item->ExValue3=$this->Entry->Value->Format;
        }else{
            $item->Value=$this->Entry->Value->Name;
            $item->ExValue3=$this->Entry->Value->Format;
        }


        return array($item);
    }

    public function GetFirstName(){
        return Sanitizer::GetStringValueFromPath($this->Entry,['Value','FirstName']);
    }

    public function GetLastName(){
        return Sanitizer::GetStringValueFromPath($this->Entry,['Value','LastName']);
    }


    public function InternalToText()
    {
        $name='';
        if($this->Entry->Value->Format=='first_and_last')
            $name=$this->Entry->Value->FirstName.' '.$this->Entry->Value->LastName;
        else
            $name=$this->Entry->Value->Name;

        return $name;
    }

    public function GetSubSections()
    {
        return [
            (object)["PathId"=>"FirstName","Column"=>"exvalue1"],
            (object)["PathId"=>"LastName","Column"=>"exvalue2"],
        ];
    }


}