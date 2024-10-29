<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class MultipleOptionsCSVColumn extends CSVColumn
{

    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource);
        if($value==null)
            return '';

        $values= ArrayUtils::Map($value,function ($item){return $item->Label;});
        return \implode(', ',$values);
    }
}