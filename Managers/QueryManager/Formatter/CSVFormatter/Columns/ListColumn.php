<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;

class ListColumn extends CSVColumn
{


    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource);

        if($value==null)
            return '';
        return $this->Field->ToText();


    }
}