<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class TextualImageCSVColumn extends CSVColumn
{

    public function Format($dataSource)
    {
        $value=$this->GetValue($dataSource,['Value','Texts']);
        if($value==null)
            return null;

        $texts=ArrayUtils::Map($value,function ($item){
            return ($item->Label!=''?$item->Label.' ':''). $item->Value;
        });
        return \implode(', ',$texts);
    }
}