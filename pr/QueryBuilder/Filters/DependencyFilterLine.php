<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Filters;

use rednaoeasycalculationforms\pr\QueryBuilder\QueryElement\Dependency;

class DependencyFilterLine extends FilterLineBase
{
    /** @var Dependency */
    public $Dependencies;

    /**
     * @param $dependency Dependency
     */
    public function AddDependency($dependency)
    {
        $this->Dependencies[]=$dependency;
        return $this;
    }
}