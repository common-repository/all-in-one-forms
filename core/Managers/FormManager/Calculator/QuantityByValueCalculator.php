<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class QuantityByValueCalculator extends CalculatorBase
{
    public function __construct($field)
    {
        parent::__construct($field);
    }

    public function ExecutedCalculation($value)
    {
        if($value==null)
            $value=$this->Field->GetValue();

        $regularPriceToUse=$this->Field->GetRegularPrice();

        if($this->Field->IsUsed())
        {
            return $this->CreateCalculationObject($regularPriceToUse*$value,'',1);
        }else
            return $this->CreateCalculationObject('','',1);




    }
}