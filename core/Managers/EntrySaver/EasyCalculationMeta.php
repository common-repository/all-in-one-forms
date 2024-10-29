<?php


namespace rednaoeasycalculationforms\core\Managers\EntrySaver;


class EasyCalculationMeta
{
    public $EntryId;
    public $MetaName;
    public $MetaValue;
    public $DataType;
    public $IsVisible;
    public $DisplayValue;
    public $DisplayLabel;
    public function __construct()
    {
        $this->DataType='string';
        $this->IsVisible=true;
    }


    public function Load($currentMeta)
    {
        $this->EntryId=$currentMeta->EntryId;
        $this->MetaName=$currentMeta->MetaName;
        $this->MetaValue=$currentMeta->MetaValue;
        $this->DataType=$currentMeta->DataType;
        $this->IsVisible=$currentMeta->IsVisible;
        $this->DisplayValue=$currentMeta->DisplayValue;
        $this->DisplayLabel=$currentMeta->DisplayLabel;
    }
}