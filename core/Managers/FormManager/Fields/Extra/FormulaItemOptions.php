<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields\Extra;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBMultipleOptionsField;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\DTO\FieldBaseOptionsDTO;
use rednaoeasycalculationforms\DTO\ItemOptionsDTO;

class FormulaItemOptions
{
    /** @var FBMultipleOptionsField */
    public $Field;
    /** @var ItemOptionsDTO */
    public $CurrentOptionsToCheck;
    public $OptionToCheckList;
    public function __construct($field,$currentOptionsToCheck,$optionsToCheckList)
    {
        $this->Field=$field;
        $this->CurrentOptionsToCheck=$currentOptionsToCheck;
        $this->OptionToCheckList=$optionsToCheckList;
    }

    public function GetColumnValue($columnId)
    {
        $columnToSearch=ArrayUtils::Find($this->Field->Options->AdditionalOptionColumn,function ($item)use($columnId){
            return $item->Id==$columnId||$item->Label==$columnId;
        });

        if($columnToSearch==null)
            return null;

        $columnValue=ArrayUtils::Find($this->CurrentOptionsToCheck->AdditionalOptionValue,function ($item)use($columnToSearch){
            return $item->Id==$columnToSearch->Id;
        });

        if($columnValue==null)
            return null;

        return $columnValue->Value;

    }

    public function __toString()
    {
        return $this->CurrentOptionsToCheck->RegularPrice;
    }

    public function ToNumber()
    {
        return floatval($this->CurrentOptionsToCheck->RegularPrice);
    }


}