<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class GroupPricePerItemCalculator extends CalculatorBase
{

    public function ExecutedCalculation($value)
    {
        $values=$this->Field->GetValue();
        if(count((array)$values)>0)
        {
            $regularPrice=$this->Field->GetRegularPrice();
            return $this->CreateCalculationObject($regularPrice,0,count((array)$values));
        }else
            return $this->CreateCalculationObject(0,0,0);
    }
}