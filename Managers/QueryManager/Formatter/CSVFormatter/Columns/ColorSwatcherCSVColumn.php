<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use Exception;

class ColorSwatcherCSVColumn extends CSVColumn
{

    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource);
        if($value==null)
            return '';
        throw new Exception('Color swatcher not implemented');
    }
}