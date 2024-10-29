<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Filters;


use rednaoeasycalculationforms\pr\QueryBuilder\QueryBuilder;

class FilterGroup
{
    /** @var FilterLineBase[] */
    public $FilterLines;

    /** @var QueryBuilder */
    public $QueryBuilder;
    public $JoinType;
    public function __construct($queryBuilder,$joinType='or')
    {
        $this->JoinType=$joinType;
        $this->FilterLines=[];
        $this->QueryBuilder=$queryBuilder;
    }

    /**
     * @param $line FilterLineBase
     */
    public function AddFilterLine($line)
    {
        $this->FilterLines[]=$line;
    }

    public function CreateFilterLine(){
        $filterLine=new FilterLineBase($this);
        $this->AddFilterLine($filterLine);
        return $filterLine;
    }

    /**
     * @param array $addedDependencies
     * @return string
     */
    public function CreateGroupString(){
        $group='(';

        for($i=0;$i<count($this->FilterLines);$i++)
        {
            $currentLine=$this->FilterLines[$i];
            if($i>0)
                $group.=' and ';


            $comparison=$currentLine->Filter->CreateComparison();
            $dependenciesToAdd=[];
            if($this->QueryBuilder!=null)
                foreach($currentLine->Dependencies as $currentDependency)
                {
                    if($currentDependency->NegateExist|| !$this->QueryBuilder->HasDependency($currentDependency))
                    {
                        $dependenciesToAdd[]=$currentDependency;
                    }

                }

            if(count($dependenciesToAdd)>0)
                foreach($dependenciesToAdd as $dependency)
                {
                    $group.=($dependency->NegateExist?' not ':''). ' exists('. $dependency->CreateSubQuery();
                    if($currentLine->Filter!=null)
                        $group.=' and '.$comparison;

                    $group.=' )';

                }
            else
                $group.=$comparison;
        }

        $group.=' ) ';
        return $group;
    }

}