<?php


namespace rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator;


use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;

class FixedSource implements ComparisonSource
{
    private $Value;
    /**
     * VariationSource constructor.
     * @param $model FormBuilder
     */
    public function __construct($model,$value)
    {
        $this->Value=$value;
    }

    public function SetValue($value)
    {
        $this->Value=$value;
    }


    public function GetValue()
    {
        return $this->Value;
    }

    public function IsUsed()
    {
        return true;
    }
}