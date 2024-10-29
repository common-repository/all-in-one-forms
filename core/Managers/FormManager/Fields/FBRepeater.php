<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;



use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\GroupOfFieldsInGroupCalculator;
use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\GroupPricePerItemCalculator;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerManager;
use rednaoeasycalculationforms\core\Managers\FormManager\FBColumn;
use rednaoeasycalculationforms\core\Managers\FormManager\FBRow;
use rednaoeasycalculationforms\DTO\RepeaterFieldOptionsDTO;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use stdClass;


class FBRepeater extends FBFieldBase implements ContainerDataRetriever
{
    public $TemplateRows;
    /** @var FBFieldBase []*/
    public $Fields;
    public $Entries;
    /** @var FBRow[] */
    public $Rows;
    /** @var ContainerManager */
    public $ContainerManager;

    /**
     * @param $loader
     * @param $fbColumn
     * @param $options RepeaterFieldOptionsDTO
     * @param null $entry
     */
    public function __construct($loader, $fbColumn, $options,$entry=null)
    {
        parent::__construct($loader, $fbColumn, $options,$entry);
        $this->ContainerManager=new ContainerManager($this);
        if($options->PriceType=='price_per_item')
            $this->Calculator=new GroupPricePerItemCalculator($this);
        if($options->PriceType=='sum_of_fields_in_group')
            $this->Calculator=new GroupOfFieldsInGroupCalculator($this);

        $this->Entries=new stdClass();
        $this->Entries->Fields=$this->GetEntryValue('Value',array());
       // $this->Entry=(Object)["Id"=>$this->Options->Id, 'Data'=>Sanitizer::GetValueFromPath( $this->Entry,['Value'],[]),'Type'=>'repeater'];

        foreach($options->RepeaterItemTemplate->Rows as $currentRow)
        {
            $this->TemplateRows[]=new FBRow($this->Loader,$this,$currentRow);
        }

        if(isset($this->TemplateRows))
            foreach($this->TemplateRows as $Row)
                foreach ($Row->Columns as $Column)
                    $this->Fields[]=$Column->Field;

        $this->Rows=[];

        foreach ($this->Entries->Fields as $repeaterItem)
        {

            $row=new FBRow($this->Loader,$this,null,null);
            $column=new FBColumn($this->Loader,$row,null,null);
            $row->AddColumn($column);
            $column->AddField(new FBRepeaterItem($this->Loader,$column,$options->RepeaterItemTemplate,$repeaterItem,Sanitizer::SanitizeNumber( Sanitizer::GetValueFromPath($repeaterItem,['Total']))));

            $this->Rows[]=$row;
           /* $this->CreatedItems[]=&$newRepeaterItem;

            foreach($repeaterItem as $itemField)
            {
                foreach($this->Fields as $fieldOptions)
                {
                    if($fieldOptions->Options->Id==$itemField->Id)
                    {

                        $newRepeaterItem[]= FieldFactory::GetField($loader,null,\unserialize(\serialize($fieldOptions->Options)),$itemField);
                    }
                }
            }*/
        }



    }



    /**
     * @return FBRepeaterItem[]
     */
    public function GetRepeaterItems()
    {
        $fields=[];
        foreach ($this->Rows as $row)
            foreach($row->Columns as $column)
            {
                $fields[]=$column->Field;
            }
        return $fields;
    }

    public function GetLineItems()
    {
        return $this->ContainerManager->GetLineItems();

    }

    public function PrepareForSerialization()
    {
        $this->ContainerManager->PrepareForSerialization();
    }
    public function CommitFiles()
    {
        $this->ContainerManager->CommitFiles();
    }

    public function GetPriceOfNotDependantFields()
    {
        $total=0;
        foreach($this->Fields as $field)
        {
            if(!$field->Calculator->GetDependsOnOtherFields())
                $total+=$field->Calculator->GetPrice();
        }

        return $total;
    }

    public function InternalToText()
    {
        return $this->ContainerManager->ToText();
    }

    public function InternalIsUsed()
    {
        return $this->Entry!=null&&count($this->GetFieldsEntryData())>0;
    }

    public function GetHtml($context = null)
    {
        return $this->ContainerManager->GetHtml($context);
    }

    /**
     * @inheritDoc
     */
    public function GetRows()
    {
        return $this->Rows;
    }

    public function GetContainerManager()
    {
        return $this->ContainerManager;
    }


    public function GetLoader()
    {
        return $this->Loader;
    }

    public function GetFieldsEntryData()
    {
        return Sanitizer::GetValueFromPath( $this->Entry,['Value'],[]);
    }
}