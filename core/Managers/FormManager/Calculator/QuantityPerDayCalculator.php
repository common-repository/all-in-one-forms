<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class QuantityPerDayCalculator extends CalculatorBase
{

    public function ExecutedCalculation($value)
    {
        $value=$this->Field->GetEntryValue('Value',null);
        if($value!=null)
        {
            $startDate=$value->StartUnix;
            $endDate=$value->EndUnix;

            $value=0;
            if($startDate!=null&&$endDate!=null)
            {
                $alpha=$endDate-$startDate;
                if($alpha==0)
                    $value=1;
                else
                    $value=$alpha/(24*60*60);
            }

            return $this->CreateCalculationObject('','',$value);

        }else
            return $this->CreateCalculationObject('','',0);
    }
}