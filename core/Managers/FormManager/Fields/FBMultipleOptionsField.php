<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use Exception;
use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\MultipleValueComparator;
use rednaoeasycalculationforms\core\Managers\ConditionManager\ConditionManager;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\Extra\FormulaItemOptions;
use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;
use rednaoeasycalculationforms\DTO\ItemOptionsDTO;
use rednaoeasycalculationforms\DTO\MultipleOptionsBaseOptionsDTO;
use rednaoeasycalculationforms\Managers\FormulaManager\FormulaManager;
use rednaoeasycalculationforms\Parser\Core\DataRetriever;
use rednaoeasycalculationforms\Parser\Elements\ParseMain;
use rednaoeasycalculationforms\Utilities\AssertionUtils;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use stdClass;

class FBMultipleOptionsField extends FBFieldWithPrice
{
    /** @var MultipleOptionsBaseOptionsDTO */
    public $Options;
    public $optionsToReturn=null;
    public $OptionsToUse=array();

    /**
     * @return ItemOptionsDTO[]
     */
    public function GetSelectedOptions(){
        if($this->Entry==null)
            return null;
        return $this->Entry->SelectedValues;
    }

    public function GetComparator()
    {
        return new MultipleValueComparator($this->GetForm(),$this);
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer); // TODO: Change the autogenerated stub
        $manager=$sanitizer->AddArraySanitizer('SelectedValues')->Manager;
        $manager->AddNumericSanitizer('Id');
        $manager->AddStringSanitizer('Label');
        $manager->AddStringSanitizer('RegularPrice');
        $manager->AddNumericSanitizer('Quantity',0,false);
        $manager->AddNumericSanitizer('UnitPrice',0,false);
        $manager->AddNumericSanitizer('Price',0,false);


        $manager=$manager->AddArraySanitizer('AdditionalOptionValue')->Manager;

        $manager->AddStringSanitizer('Value');
        $manager->AddNumericSanitizer('Id');
    }

    public function GetOptionLabel($option)
    {
        $label=$option->Label;
        $quantity=$this->GetEntryValue('Quantity',1);
        if($quantity>1)
            $label=$quantity.' x '.$label;

        return $label;
    }


    public function SanitizeEntry()
    {
        parent::SanitizeEntry(); // TODO: Change the autogenerated stub
        if($this->Entry==null)
            return;
        /*$selectedValues=[];
        foreach ($this->Entry->SelectedValues as &$entryOptions)
        {
            $this->OptionsToUse=$this->GetOptions();
            foreach($this->OptionsToUse as $originalOptions)
            {
                if($originalOptions->Id==$entryOptions->Id)
                {
                    $entryOptions =\json_decode(\json_encode($originalOptions));
                    $entryOptions->total=new stdClass();
                    $entryOptions->total->Price=0;
                    $entryOptions->total->Quantity=0;
                    $entryOptions->total->RegularPrice=0;
                    $entryOptions->total->SalePrice=0;


                    if(isset($originalOptions->SalePrice)&&\is_numeric($originalOptions->SalePrice))
                        $entryOptions->UnitPrice=\floatval($originalOptions->SalePrice);
                    else
                        $entryOptions->UnitPrice=\floatval($originalOptions->RegularPrice);
                    $entryOptions->Quantity=1;
                    $entryOptions->Price=$entryOptions->UnitPrice;
                    $entryOptions->Selected=true;
                    $selectedValues[]=$entryOptions->Label;
                }
            }
        }*/

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
            $newItem->Value=$currentOption->Label;
            if(isset($currentOption->total))
                $newItem->UnitPrice=$currentOption->total->Price;
            else
                $newItem->UnitPrice=0;
            $itemList[]=$newItem;
        }

       return $itemList;
    }

    public function Validate()
    {

        if($this->Entry!=null) {

            $selectedValues = [];
            $optionsToCheck = $this->Options->Options;

            $conditions = $this->GetConditionByType('ChangeOptions');
            foreach ($conditions as $condition) {
                $conditionManager = new ConditionManager();
                if ($conditionManager->ShouldProcess($this->GetForm(), $condition)) {
                    $optionsToCheck = $condition->Options;
                }
            }

            $attributeToUse = null;
            $conditions = $this->GetConditionByType('ChangeOptionsPrice');
            foreach ($conditions as $condition) {
                $conditionManager = new ConditionManager();
                if ($conditionManager->ShouldProcess($this->GetForm(), $condition)) {

                    $optionsToCheck = json_decode(json_encode($optionsToCheck));

                    foreach ($this->Options->AdditionalOptionColumn as $currentColumn) {
                        if (Sanitizer::GetStringValueFromPath($currentColumn, ["Options", 'Id']) == $condition->Id) {
                            $attributeToUse = $currentColumn->Id;
                            foreach ($optionsToCheck as $currentValue) {
                                $found = false;
                                foreach ($currentValue->AdditionalOptionValue as $currentOptionValue) {
                                    if ($currentOptionValue->Id == $attributeToUse) {
                                        $found = true;
                                        $currentValue->RegularPrice = $currentOptionValue->Value;
                                    }
                                }
                                if (!$found) {
                                    $currentValue->RegularPrice = 0;
                                }
                            }
                        }
                    }
                }
            }

            if($this->Options->PriceType=='formula_item')
            {
                $formula=FormulaManager::GetFormula($this,'Price');
                $fields=$this->GetForm()->ContainerManager->Getfields(false,true,true);
                $formula=FormulaManager::GetFormula($this,'Price');

                foreach($optionsToCheck as $currentOptionToCheck)
                {
                    $parser=new ParseMain($formula->Compiled,new DataRetriever($this->GetForm(),function($retriever,$id,$op) use($optionsToCheck,$currentOptionToCheck){
                        if($id=='current_option')
                        {
                            return new FormulaItemOptions($this,$currentOptionToCheck,$optionsToCheck);

                        }
                    }));
                    $currentOptionToCheck->RegularPrice=$parser->Parse();
                }




            }


            foreach ($this->Entry->SelectedValues as $submittedOptions) {
                $found = false;
                foreach ($optionsToCheck as $dbOptions) {
                    if ($dbOptions->Id == $submittedOptions->Id) {
                        $found = true;
                        foreach ($dbOptions as $property => $value) {

                            if (isset($submittedOptions->$property) && !AssertionUtils::ValueIsEqual($submittedOptions->$property, $value)) {
                                $this->AddError('The option values does not match, expected ' . $value . ' but got ' . $submittedOptions->$property);
                                return false;
                            }
                        }
                    }
                }

                if (!$found) {
                    $this->AddError('Invalid option selected');
                    return false;
                }
            }

        }
        parent::Validate();
        return true;




    }

    public function GetValue(){
        return $this->GetSelectedOptions();
    }

    public function InternalToText()
    {
        if($this->GetRootForm()->IsTest)
            return '[Test Value]';
        $values=$this->GetValue();
        $labels=\array_map(function ($value){return $value->Label;},$this->GetValue());
        return \implode(', ',$labels);
    }

    public function GetHTMLTemplate($context=null)
    {
        return 'core/Managers/FormManager/Fields/FBMultipleOptionsField.twig';
    }


    protected function InternalGetHtml($document, $formatter=null){
        $tag=null;
        if(count($this->GetSelectedOptions())<=1)
        {
            $tag = new HtmlTagWrapper($document, $document->createElement('span'));
            $tag->SetText($this->ToText());
        }else{
            $tag=new HtmlTagWrapper($document,$document->createElement('ul'));
            $tag->AddStyle('padding',0);
            $tag->AddStyle('margin',0);


            foreach($this->GetSelectedOptions() as $option)
            {
                $item=$tag->CreateAndAppendChild('li');
                $item->AddStyle('padding',0);
                $item->AddStyle('margin',0);
                $item->AddStyle('margin-bottom','3px');
                $item->AddStyle('list-style-position','inside');
                $item->SetText($option->Label);
            }

        }
        return $tag;

    }

    public function GetSelectedValues(){
        if($this->Entry==null)
            return [];
        return \array_map(function ($value){return $value->RegularPrice;},$this->GetSelectedOptions());
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

    public function GetHtml($context = null)
    {
        if($this->GetRootForm()->IsTest)
            return '[Test Value]';
        return parent::GetHtml($context); // TODO: Change the autogenerated stub
    }




}

