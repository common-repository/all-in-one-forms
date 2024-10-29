<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields\LineItems\Core;


class LineItem
{
    public $EntryId;
    public $FieldId;
    public $UniqId;
    public $Value;
    public $ExValue1;
    public $ExValue2;
    public $ExValue3;
    public $ExValue4;
    public $ExValue5;
    public $ExValue6;
    public $NumericValue;
    public $NumericValue2;
    public $DateValue;
    public $DateValue2;
    public $UserId;
    public $UnitPrice;
    public $TotalFieldPrice;
    public $Type;
    public $SubType;

    public function __construct()
    {
        $this->EntryId=null;
        $this->FieldId=null;
        $this->Value=null;
        $this->ExValue1=null;
        $this->NumericValue=null;
        $this->DateValue=null;
        $this->UserId=null;
        $this->UniqId=0;
        $this->TotalFieldPrice=0;
        $this->UnitPrice=0;
        $this->DateValue2=null;
        $this->Type=null;
        $this->NumericValue2=null;
        $this->SubType=null;
    }


    public function Serialize(){
        $data=array();

        $data->field_id=$this->FieldId;
        $data->value=$this->Value;
        if($this->ExValue1!=null)
            $data->exvalue1=$this->ExValue1;

        if($this->NumericValue!==null)
            $data->numericvalue=$this->NumericValue;

        if($this->DateValue!==null)
            $data->datevalue=$this->DateValue;

    }


    public function CloneItem(){
        $lineItem=new LineItem();
        $lineItem->EntryId=$this->EntryId;
        $lineItem->FieldId=$this->FieldId;
        $lineItem->UniqId=$this->UniqId;
        $lineItem->Value=$this->Value;
        $lineItem->ExValue1=$this->ExValue1;
        $lineItem->ExValue2=$this->ExValue2;
        $lineItem->ExValue3=$this->ExValue3;
        $lineItem->ExValue4=$this->ExValue4;
        $lineItem->ExValue5=$this->ExValue5;
        $lineItem->ExValue6=$this->ExValue6;
        $lineItem->NumericValue=$this->NumericValue;
        $lineItem->DateValue=$this->DateValue;
        $lineItem->UserId=$this->UserId;
        $lineItem->UnitPrice=$this->UnitPrice;
        $lineItem->TotalFieldPrice=$this->TotalFieldPrice;
        $lineItem->Type=$this->Type;
        $lineItem->NumericValue2=$this->NumericValue2;
        $lineItem->SubType=$this->SubType;
        return $lineItem;

    }


}