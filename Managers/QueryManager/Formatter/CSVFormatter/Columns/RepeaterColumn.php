<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;

class RepeaterColumn extends CSVColumn
{
    /** @var CSVColumn[] */
    public $Columns;
    public function __construct($loader, $title, $path, $field)
    {
        parent::__construct($loader, $title, $path, $field);
        $this->Columns=[];
        foreach ($field->TemplateRows as $row)
            foreach ($row->Columns as $column)
            {
                $this->Columns=\array_merge($this->Columns,CSVColumnFactory::GetCSVColumnByField($loader,$column->Field));
            }



    }

    public function GetHeaders()
    {
        $headers=[];
        foreach ($this->Columns as $column)
            $headers=\array_merge($headers,$column->GetHeaders());
        return $headers;
    }

    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource);
        if(!isset($value->Value))
        {
            return [array_fill(0,count($this->GetHeaders()),'')];
        }

        $rows=[];
        foreach($value->Value as $row)
        {
            $rowValues=[];
            foreach ($this->Columns as $column)
            {
                $columns=$column->Format((object)['data'=>$row->Value]);
                if($column->CanAddMultipleRows())
                    $rowValues=\array_merge($rowValues,$columns[0]);
                else
                    $rowValues[]=$columns;
            }
            $rows[]=$rowValues;
        }
       return $rows;

    }

    public function CanAddMultipleRows()
    {
        return true;
    }




}