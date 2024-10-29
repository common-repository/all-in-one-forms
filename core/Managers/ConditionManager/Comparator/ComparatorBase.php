<?php


namespace rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;

abstract class ComparatorBase
{
    /** @var ComparisonSource */
    public $Source;
    /** @var FormBuilder */
    public $Model;

    public function __construct($model,$source)
    {
        $this->Model=$model;
        $this->Source=$source;
    }

    public abstract function Compare($ComparisonType,$Value);
}