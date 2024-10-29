<?php


namespace rednaoeasycalculationforms\Managers\QueryManager\Formatter\CSVFormatter\Columns;


class BasicStringCSVColumn extends CSVColumn
{

    public function Format($dataSource)
    {
        return $this->GetValueString($dataSource);
    }
}