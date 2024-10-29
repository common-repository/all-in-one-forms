<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;



use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\GroupCalculator;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerManager;
use rednaoeasycalculationforms\core\Managers\FormManager\FBRow;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class FBRepeaterItem extends FBFieldBase implements ContainerDataRetriever
{
    /**
     * @var FBRow
     */
    public $Rows;
    /** @var ContainerManager */
    public $ContainerManager;
    public $Total=0;
    public function __construct($loader, $fbColumn, $options, $entry = null,$total=0)
    {
        parent::__construct($loader, $fbColumn, $options, $entry);
        $this->Options->Type='repeateritem';
        $this->ContainerManager=new ContainerManager($this);
        $this->Calculator=new GroupCalculator($this);
        $this->Total=$total;

        foreach ($options->Rows as $row)
        {
            $row=new FBRow($this->Loader,$this,$row,Sanitizer::GetValueFromPath($entry,['Value'],[]));
            if(count($row->Columns)>0)
                $this->Rows[]=$row;
        }



/*

        foreach($this->Rows as $row)
        {
            foreach($row->Columns as $column)
            {
                $field=$column->Field;
                if(!$field->Calculator->GetDependsOnOtherFields())
                    $field->Calculator->ExecuteAndUpdate();;
            }

            foreach($row->Columns as $column)
            {
                $field=$column->Field;
                if($field->Calculator->GetDependsOnOtherFields())
                    $field->Calculator->ExecuteAndUpdate();;
            }
        }
*/

    }

    public function GetLineItems()
    {
        return $this->ContainerManager->GetLineItems();

    }

    public function GetValue()
    {
        return $this->Entry;
    }

    public function GetId()
    {
        if($this->GetForm()==null)
            return 0;

        for($i=0;$i<count($this->GetForm()->Rows);$i++)
        {
            if($this->GetForm()->Rows[$i]===$this->Column->Row)
                return $i+1;
        }

        return 0;
    }

    public function PrepareForSerialization()
    {
        $this->ContainerManager->PrepareForSerialization();
    }

    public function CommitFiles()
    {
        $this->ContainerManager->CommitFiles();
    }

    public function InternalToText()
    {
        return $this->ContainerManager->ToText();
    }


    public function GetAllFields()
    {
        $fields=[];
        foreach ($this->Rows as $row)
            foreach($row->Columns as $column)
            {
                $fields[]=$column->Field;
            }
        return $fields;
    }

    /**
     * @inheritDoc
     */
    public function GetRows()
    {
        return $this->Rows;
    }


    public function GetLoader()
    {
        return $this->Loader;
    }

    public function GetContainerManager()
    {
        return $this->ContainerManager;
    }


    public function GetFieldsEntryData()
    {
        return Sanitizer::GetValueFromPath($this->Entry,['Data']);
    }
}