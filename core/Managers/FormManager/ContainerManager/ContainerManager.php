<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager;



use rednaoeasycalculationforms\core\Exception\FriendlyException;
use rednaoeasycalculationforms\core\Managers\FormManager\FBRow;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBRecaptcha;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBRepeaterItem;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\LineItems\Core\LineItem;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLContextBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class ContainerManager
{

    /** @var ContainerDataRetriever */
    public $Container;
    public $LineItems;


    public function __construct($Container)
    {
        $this->Container=$Container;
        $this->LineItems=null;

    }

    public function GetTotal(){
        if(isset($this->Container->GrandTotal))
            return Sanitizer::SanitizeNumber($this->Container->GrandTotal);

        if(isset($this->Container->Total))
            return Sanitizer::SanitizeNumber($this->Container->Total);

        return 0;
    }

    /**
     * @return TemplateColumnRenderer[]
     */
    public function GetHeaderColumns(){
        return [];
    }
    public function GetFormBuilder(){
        return Sanitizer::GetValueFromPath($this->Container,['Column','Row','Form']);
    }

    public function GetUnitPrice()
    {
        $fields=$this->GetFields(false,false,false);
        $unitPrice=0;
        foreach($fields as $currentField)
        {
            $unitPrice+=Sanitizer::GetNumberValueFromPath($currentField,['Entry','Price']);
        }

        return $unitPrice;
    }

    public function GetGrandTotal()
    {
        return $this->GetUnitPrice()*$this->GetQuantity();
    }

    public function GetQuantity()
    {
        $fields=$this->GetFields(false,false,false);
        $quantityField=null;
        foreach($fields as $currentField)
        {
            if(Sanitizer::GetStringValueFromPath($currentField,['Options','PriceType'])=='quantity'
                ||Sanitizer::GetStringValueFromPath($currentField,['Options','PriceType'])=='quantity_per_day'
                ||$currentField->Calculator!=null&&$currentField->Calculator->CanCalculateQuantity()
            ) {

                $quantityField = $currentField;
                break;
            }

        }

        if($quantityField==null)
            return 1;

        return $quantityField->Calculator->GetQuantity();
    }

    /**
     * @return FormBuilder
     */
    public function GetRootFormBuilder(){
        $form=$this->GetFormBuilder();
        if($form==null)
            return $this->Container;

        return $form->ContainerManager->GetRootFormBuilder();
    }

    /**
     * @param bool $includeFieldsOfRepeaters
     * @param bool $IncludeFieldsOfParentContainers
     * @param bool $includeFieldsOfGroupPanel
     * @return FBFieldBase[]
     */
    public function GetFields($includeFieldsOfRepeaters=false,$IncludeFieldsOfParentContainers=false,
                              $includeFieldsOfGroupPanel=true) {
        /** @var FBFieldBase[] $fields */
        $fields=[];

        if($this->Container->Rows==null)
            return $fields;
        foreach($this->Container->Rows as $row)
        {
            foreach($row->Columns as $column)
            {
                $field=$column->Field;
                $fields[]=$field;

                if($field->Options->IsFieldContainer&&$field->Options->Type!='repeater'&&$field->Options->Type!='repeateritem'&&$includeFieldsOfGroupPanel)
                {
                    foreach($field->ContainerManager->GetFields(true) as $subField)
                    {
                        $fields[]=$subField;
                    }
                }

                if($includeFieldsOfRepeaters&&($field->Options->Type=='repeater'||$field->Options->Type=='repeateritem'))
                {
                    foreach($field->ContainerManager->GetFields(true) as $subField)
                        $fields[]=$subField;
                }
            }
        }

        if($IncludeFieldsOfParentContainers)
        {
            if($this->Container->GetForm()!=null)
                $fields=\array_merge($fields,array_values(\array_filter($this->Container->GetForm()->ContainerManager->GetFields(false,true),
                    function ($element) use($fields){
                        $found=false;
                       foreach($fields as $currentField)
                       {
                           if($currentField->Options->Id==$element->Options->Id)
                               $found=true;
                       }
                        return $found;
                    })));
        }

        return $fields;


    }

    /**
     * @param $id
     * @param $searchInChildContainer
     * @param $searchInParentContainers
     * @param $repeaterSearchType false|"EntryAll"|"EntryFirst"|"FieldTemplate"
     * @return FBFieldBase|null|FBFieldBase[]
     */
    public function GetFieldById($id,$searchInChildContainer=false,$searchInParentContainers=false,$repeaterSearchType=false){
        foreach($this->GetFields() as $field)
        {
            if($field->Options->Id== $id)
                return $field;

            if($field->Options->Type=='repeater')
            {


                switch ($repeaterSearchType)
                {
                    case false:
                        continue 2;
                    case "FieldTemplate":
                        /** @var FBRow $rowItem */
                        foreach($field->TemplateRows as $rowItem)
                        {
                            foreach($rowItem->Columns as $column)
                            {
                                if($column->Field->Options->Id==$id)
                                    return $column->Field;
                            }
                        }
                    case "EntryAll":
                        $fields=[];
                        /** @var FBRepeaterItem[] $repeaterItems */
                        $repeaterItems=$field->ContainerManager->GetFields();
                        foreach($repeaterItems as $currentItem) {
                            $field = $currentItem->ContainerManager->GetFieldById($id);
                            if($field != null)
                                $fields[] = $field;
                        }
                        if(count($fields)>0)
                            return $fields;
                        break;
                    default:
                        throw new FriendlyException("Invalid repeater search type ".$repeaterSearchType);


                }
            }

            if($searchInChildContainer&&isset($field->ContainerManager))
            {
                $field=$field->ContainerManager->GetFieldById($id,true,);
            }

            if($searchInParentContainers&&$this->Container->GetForm()!=null)
            {
                return $this->Container->GetForm()->ContainerManager->GetFieldById($id,false,true);
            }
        }

        return null;
    }

    /**
     * @return HTMLContextBase
     */
    public function GetHtml($context,$options=null)
    {

        return (new ContainerManagerRenderer($this,$context,$options))->Render();

    }

    public function PrepareForSerialization(){
        foreach ($this->GetFields(false,false,false) as $field)
        {
            if ($field->Entry == null&&!($field instanceof FBRecaptcha))
                continue;

            $field->PrepareForSerialization();
        }
    }


    /**
     * @return LineItem[]
     */
    public function GetLineItems()
    {
        if($this->LineItems==null)
        {

            $this->LineItems = array();
            foreach ($this->GetFields(false,false,false) as $field)
            {
                if (!$field->IsUsed())
                    continue;

                $lineItems=$field->GetLineItems();
                if($lineItems==null)
                    continue;
                $this->LineItems = \array_merge($this->LineItems, $lineItems);
            }
        }

        return $this->LineItems;

    }

    public function ToText()
    {
        $text=[];
        foreach($this->Container->GetRows() as $row)
        {

            foreach ($row->Columns as $column)
            {
                $currentField = $column->Field;
                if (!$currentField->IsUsed())
                    continue;

                $text[]=$currentField->GetLabel().":". $currentField->ToText();


            }


        }
        return \implode('|| ',$text);

    }

    public function CommitFiles()
    {
        foreach ($this->GetFields(false,false,false) as $field)
        {
            if ($field->Entry == null)
                continue;

            $field->CommitFiles();
        }
    }


}