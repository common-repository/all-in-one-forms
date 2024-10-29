<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\QueryElement;


use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonBase;

class Dependency
{
    public $TableName;
    public $Id;
    public $NegateExist;

    /** @var ComparisonBase[] */
    public $Comparisons;

    public function __construct($tableName,$id,$negateExist=false)
    {
        $this->NegateExist=$negateExist;
        $this->TableName=$tableName;
        $this->Id=$id;
    }

    public function AddComparison($comparison)
    {
        $this->Comparisons[]=$comparison;
        return $this;
    }

    public function CreateJoin()
    {
        $join=' left join ';
        $join.=$this->TableName.' '.$this->Id;
        $join.=' on ';

        for($i=0;$i<count($this->Comparisons);$i++)
        {
            if($i>0)
                $join.=' and ';
            $join.=$this->Comparisons[$i]->CreateComparison();
        }

        return $join;
    }

    public function CreateSubQuery()
    {
        $subQuery='select * from '.$this->TableName.' '.$this->Id.' where ';

        for($i=0;$i<count($this->Comparisons);$i++)
        {
            if($i>0)
                $subQuery.=' and ';
            $subQuery.=$this->Comparisons[$i]->CreateComparison();
        }

        return $subQuery;

    }


}