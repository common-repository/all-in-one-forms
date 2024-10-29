<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class QuantityCalculator extends CalculatorBase
{
    public function __construct($field)
    {
        parent::__construct($field);
    }

    public function ExecutedCalculation($value)
    {

        return $this->CreateCalculationObject('','',$this->Field->ToNumber());


    }
}