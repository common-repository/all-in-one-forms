<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Loader;
use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;

abstract class CSVColumn
{
    public $IsNumeric=false;
    public $Width=200;
    /** @var FBFieldBase */
    public $Parent=null;
    public $Title;
    public $Path;
    /** @var FBFieldBase */
    public $Field;
    /** @var Loader */
    public $Loader;
    public $ValueRetriever=null;
    /**
     * CSVColumnFormatter constructor.
     * @param bool $IsNumeric
     */
    public function __construct($loader,$title,$path,$field)
    {
        $this->Loader=$loader;
        $this->Title=$title;
        $this->Path=$path;

        $this->Field=$field;
    }

    public function SetParent($parent)
    {
        $this->Parent=$parent;
        return $this;
    }

    public function SetWidth($width)
    {
        $this->Width=$width;
        return $this;
    }

    public function SetIsNumerid(){
        $this->IsNumeric=true;
        return $this;
    }

    public function GetHeaders(){
        return [$this->Title];
    }

    public abstract function Format($dataSource);

    public function CanAddMultipleRows(){
        return false;
    }

    public function GetValue($dataSource,$path=null)
    {
        if($this->Parent!=null)
        {
            $dataSource=$this->GetChildDataSource($dataSource->data);
            if($dataSource==null)
                return null;
        }

        $currentValue=$dataSource;

        if($this->Field!=null)
        {
            if($currentValue->data==null||!\is_array($currentValue->data))
                return null;

            $currentValue=ArrayUtils::Find($currentValue->data,function ($x){return $x->Id==$this->Field->Options->Id;});
            if($currentValue==null)
                return null;
        }

        if($this->ValueRetriever!=null)
        {
            return \call_user_func($this->ValueRetriever,$currentValue);

        }



        if($path==null)
            $path=$this->Path;

        if($path==null)
            return $currentValue;

        foreach($path as $currentPath)
        {
            if(!isset($currentValue->$currentPath))
                if(is_array($currentValue)&&isset($currentValue[$currentPath])) {
                    $currentValue = $currentValue[$currentPath];
                    continue;
                }
                else
                    return null;
            $currentValue=$currentValue->$currentPath;
        }

        return $currentValue;
    }

    public function GetValueString($dataSource,$path=null,$defaultValue='')
    {
        $value=$this->GetValue($dataSource,$path);
        if($value==null)
            return $defaultValue;
        return \strval($value);
    }

    private function GetChildDataSource($dataSource)
    {
        $currentParent=$this->Parent;
        $dataSourceIds=[$currentParent->GetId()];

        while($currentParent->GetForm()!=null&&\method_exists($currentParent->GetForm(),'GetId'))
        {
            $currentParent=$currentParent->GetForm();
            if($currentParent->GetForm()!=null)
                $dataSourceIds[]=$currentParent->GetId();
        }

        foreach($dataSourceIds as $currentId)
        {
            $dataSource=ArrayUtils::Find($dataSource,function ($item)use($currentId){return $item->Id==$currentId;});
            if($dataSource==null)
                return null;
            $dataSource=$dataSource->Value;

        }

        return (object)array('data'=>$dataSource);
    }

    public function GetNumericValue($dataSource,$path=null,$defaultValue=0)
    {
        $value=$this->GetValue($dataSource,$path);
        if($value==null)
            return $defaultValue;

        if(!\is_numeric($value))
            return $defaultValue;

        $value=\floatval($value);

        return $value;

    }

    public function SetValueRetriever($retriever)
    {
        $this->ValueRetriever=$retriever;
    }


}