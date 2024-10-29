<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use Exception;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBMultipleOptionsField;
use rednaoeasycalculationforms\core\Managers\FormManager\Utilities\NumericUtilities;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class OptionsCalculator extends CalculatorBase
{
    /** @var FBMultipleOptionsField */
    public $Field;
    public $IsValid;
    public function ExecutedCalculation($value)
    {
        $selectedOptions=$this->Field->GetSelectedOptions();

        if(count($selectedOptions)==0)
            return $this->CreateCalculationObject('','',0);

        $total=0;
        foreach($selectedOptions as $currentOption)
        {
            $result=null;

            $total+=Sanitizer::GetNumberValueFromPath($currentOption,['RegularPrice']);

        }



        return $this->CreateCalculationObject($total,'',1);



    }



    public function GetDependsOnOtherFields(){
        return false;
    }


    private function AddOption($original, $ammountToAdd)
    {
        if($ammountToAdd['RegularPrice']!='')
        {
            $original['RegularPrice']=NumericUtilities::ParseNumber($original['RegularPrice'],0)+NumericUtilities::ParseNumber($ammountToAdd['RegularPrice'],0);
        }

        if($ammountToAdd['SalePrice']!='')
        {
            $original['SalePrice']=NumericUtilities::ParseNumber($original['SalePrice'],0)+NumericUtilities::ParseNumber($ammountToAdd['SalePrice'],0);
        }

        return $original;
    }


}