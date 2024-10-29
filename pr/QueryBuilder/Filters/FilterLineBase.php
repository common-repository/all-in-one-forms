<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Filters;


use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonBase;
use rednaoeasycalculationforms\pr\QueryBuilder\QueryElement\Dependency;

class FilterLineBase
{
    /** @var Dependency[] */
    public $Dependencies;

    /** @var ComparisonBase */
    public $Filter;
    /** @var FilterGroup */
    public $FilterGroup;

    public function __construct($filterGroup)
    {
        $this->FilterGroup=$filterGroup;
        $this->Dependencies=[];
        $this->Filter=null;
    }

    /**
     * @param $dependency Dependency
     */
    public function HasDependency($dependency)
    {
        foreach($this->Dependencies as $currentDependency)
        {
            if($currentDependency->Id==$dependency->Id)
                return true;
        }

        return false;
    }


}