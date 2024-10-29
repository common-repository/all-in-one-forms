<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\QueryElement;


class QueryColumn
{
    public $Table;
    public $Column;
    public $DisplayName;
    public $Type;

    /**
     * QueryColumn constructor.
     * @param $Table
     * @param $Column
     * @param $DisplayName
     */
    public function __construct($Table, $Column, $DisplayName,$Type='standard')
    {
        $this->Table = $Table;
        $this->Column = $Column;
        $this->DisplayName = $DisplayName;
        $this->Type=$Type;
    }

    public function CreateColumn()
    {
        $table=$this->Table.'.'.$this->Column;
        if($this->Type==ColumnType::$Date)
            $table='unix_timestamp('.$table.') ';
        return $table.' '.$this->DisplayName;
    }


}
