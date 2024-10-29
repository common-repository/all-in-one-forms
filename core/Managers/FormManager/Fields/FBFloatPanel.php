<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;



use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerManager;
use rednaoeasycalculationforms\core\Managers\FormManager\FBRow;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;

use rednaoeasycalculationforms\Managers\FormManager\Calculator\GroupCalculator;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use stdClass;

class FBFloatPanel extends FBFieldBase implements ContainerDataRetriever
{
    public $Rows;
    /** @var FBFieldBase []*/
    public $Fields=[];
    public $Entries;
    /** @var ContainerManager */
    public $ContainerManager;
    public function __construct($loader, $fbColumn, $options,$entry=null)
    {
        parent::__construct($loader,$fbColumn, $options,$entry);

        $this->ContainerManager=new ContainerManager($this);

        $this->Entries=new stdClass();
        $this->Entries->Fields=$this->GetEntryValue('Value',array());
        foreach($options->Rows as $currentRow)
        {
            $this->Rows[]=new FBRow($this->Loader,$this,$currentRow,$this->Entry!=null?$this->Entry->Value:null);
        }

        if(isset($this->Rows))
        {
            foreach ($this->Rows as $Row)
                foreach ($Row->Columns as $Column)
                    $this->Fields[] = $Column->Field;
        }

    }

    protected function CanSanitize()
    {
        return true;
    }



    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        foreach($this->Fields as $currentField)
        {
            if($currentField->CanSanitize())
                $currentField->SanitizeEntry();
        }
    }

    public function GetProductRegularPrice(){
        return $this->Column->Row->Form->GetProductRegularPrice();
    }

    public function GetProductSalePrice(){
        return $this->Column->Row->Form->GetProductRegularPrice();
    }

    public function GetValue()
    {
        return $this->GetEntryValue('Value','');
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
        return Sanitizer::GetValueFromPath($this->Entry,['Data']);
    }
}