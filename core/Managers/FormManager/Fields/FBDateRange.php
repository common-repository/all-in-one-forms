<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;



class FBDateRange extends FBFieldWithPrice
{
    public function GetValue()
    {
        return $this->GetEntryValue('Value');
    }

    public function GetLineItems()
    {
        $lineItem= parent::GetLineItems()[0];
        $lineItem->Value=$this->Entry->Value->StartValue.' - '.$this->Entry->Value->EndValue;
        $lineItem->DateValue=Date('c',$this->Entry->Value->StartUnix);
        $lineItem->DateValue2=Date('c',$this->Entry->Value->EndUnix);
        return array($lineItem);
    }

    public function GetSubSections()
    {
        return [
            (object)["PathId"=>"StartDate","Column"=>"datevalue"],
            (object)["PathId"=>"EndDate","Column"=>"datevalue2"]
        ];
    }


    public function InternalToText()
    {
        return $this->Entry->Value->StartValue.' - '.$this->Entry->Value->EndValue;
    }

    public function GetDays(){
        if($this->Entry==null||$this->Entry->Value->EndUnix==0||$this->Entry->Value->StartUnix==0)
            return 0;

        return ($this->Entry->Value->EndUnix-$this->Entry->Value->StartUnix)/60/60/24;
    }

}