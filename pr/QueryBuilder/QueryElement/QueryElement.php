<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\QueryElement;


use rednaoeasycalculationforms\pr\QueryBuilder\Filters\FilterGroup;

class QueryElement
{
    /** @var Dependency[] */
    public $Dependencies;
    /** @var QueryColumn[] */
    public $Columns;
    /** @var FilterGroup[] */
    public $Filters;

    public function __construct()
    {
        $this->Dependencies=[];
    }


    public function AddDependency($dependency)
    {
        $this->Dependencies[]=$dependency;
        return $this;
    }

    public function AddColumn($queryColumn)
    {
        $this->Columns[]=$queryColumn;
        return $this;
    }

    public function AddColumn2($table,$column,$displayName,$type='standard')
    {
        $this->Columns[]=new QueryColumn($table,$column,$displayName,$type);
    }

    public function AddFilter($filterGroup)
    {
        $this->Filters[]=$filterGroup;
        return $this;
    }


}