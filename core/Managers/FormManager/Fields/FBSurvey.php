<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use Exception;
use rednaoeasycalculationforms\core\Managers\ConditionManager\ConditionManager;
use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use stdClass;

class FBSurvey extends FBFieldWithPrice
{
    public $optionsToReturn=null;
    public $OptionsToUse=array();
    public function GetSelectedOptions(){
        if($this->Entry==null)
            return null;
        return $this->Entry->SelectedValues;
    }



    public function SanitizeEntry()
    {
        parent::SanitizeEntry(); // TODO: Change the autogenerated stub
        if($this->Entry==null)
            return;
        $selectedValues=[];
        $options=$this->GetOptions();
        $rows=$options['Rows'];
        $columns=$options['Columns'];
        $this->OptionsToUse=$this->Entry->SelectedValues;
        foreach ($this->OptionsToUse as &$entryOptions)
        {
            $rowId=$entryOptions->Row->Id;
            $columnId=$entryOptions->Column->Id;
            $row=ArrayUtils::Find($rows,function($item) use($rowId){return $item->Id==$rowId;});
            $column=ArrayUtils::Find($columns,function($item) use($columnId){return $item->Id==$columnId;});

            if($row==null||$column==null)
                throw new Exception('Invalid Id');

            $entryOptions->Row=$row;
            $entryOptions->Column=$column;

            $entryOptions->RegularPrice=$column->RegularPrice;
            $entryOptions->Selected=true;

            if(isset($entryOptions->SalePrice)&&\is_numeric($entryOptions->SalePrice))
                $entryOptions->UnitPrice=\floatval($entryOptions->SalePrice);
            else
                $entryOptions->UnitPrice=\floatval($entryOptions->RegularPrice);
            $entryOptions->Quantity=1;
            $entryOptions->Price=$entryOptions->UnitPrice;
            $entryOptions->Selected=true;
        }

    }

    public function GetColumnValue($columnOrId){
        $selectedOptions=$this->GetSelectedOptions();
        $columnSearch=ArrayUtils::Find($this->Options->AdditionalOptionColumn,function ($item) use ($columnOrId){
           return $item->Id==$columnOrId||$item->Label==$columnOrId;
        });

        if($columnSearch==null)
            return null;

        $columnValues=array();
        foreach ($selectedOptions as $option)
        {
            $columnValue=ArrayUtils::Find($option->AdditionalOptionValue,function ($x) use ($columnSearch){return $x->Id==$columnSearch->Id;});
            if($columnValue!=null)
            {
                if(\is_numeric($columnValue->Value))
                {
                    $columnValues[]=\floatval($columnValue->Value);
                }
            }
        }

        return $columnValues;
    }

    public function GetLineItems()
    {
        $item= parent::GetLineItems()[0];

        $options=$this->GetSelectedOptions();

        $itemList=array();
        foreach($options as $currentOption)
        {
            $newItem=$item->CloneItem();
            $newItem->SubType=substr($currentOption->Row->Label,0,200);
            $newItem->Value=$currentOption->Column->Label;
            $newItem->UnitPrice=$currentOption->Price;
            $itemList[]=$newItem;
        }
       return $itemList;
    }


    public function GetValue(){
        return $this->GetSelectedOptions();
    }

    public function InternalToText()
    {
        $values=$this->GetValue();
        $labels=\array_map(function ($value){return $value->Label;},$this->GetValue());
        return \implode(', ',$labels);
    }

    public function ToNumber()
    {
        $selectedOptions=$this->GetSelectedOptions();
        $total=0;
        foreach($selectedOptions as $currentOption)
        {
            $regular=$currentOption->RegularPrice;
            if(!\is_numeric($regular))
                $regular=0;
            else
                $regular=\floatval($regular);

            $total+=$regular;

        }
        return $total;
    }

    public function  Contains($value)
    {
        return ArrayUtils::Find( $this->GetSelectedOptions(),function ($item)use($value){
            return $item->Label==$value;
        })!=null;

    }

    public function WasOptionSelected($row,$column)
    {
        $selectedItems=$this->GetSelectedOptions();
        foreach($selectedItems as $item)
        {
            if($row->Id==$item->Row->Id&&$column->Id==$item->Column->Id)
                return true;
        }
        return false;
    }
    public function GetHTMLTemplate($context = null)
    {
        return 'core/Managers/FormManager/Fields/FBSurvey.twig';
    }


    private function GetOptions()
    {
        $rows=$this->GetOptionValue('Rows',array());
        $columns=$this->GetOptionValue('Columns',array());

        return array(
            'Rows'=>$rows,
            'Columns'=>$columns
        );

    }

}

