<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class NoneCalculator extends CalculatorBase
{

    public function ExecutedCalculation($value)
    {
        return $this->CreateCalculationObject('','','');
    }

    public function GetIsValid()
    {
        if(isset($this->Field->ContainerManager))
        {
            $fields=$this->Field->ContainerManager->GetFields(false,false,false);
            foreach($fields as $field)
            {
                if($field->Calculator->GetIsValid()==false)
                    return false;
            }
        }
        return true;
    }


}