<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;

class GroupOfFieldsInGroupCalculator extends CalculatorBase
{

    public function ExecutedCalculation($value)
    {
        $regularPrice=0;
        /** @var ContainerDataRetriever $field */
        $repeater=$this->Field;


        $total=0;

        $items=$repeater->GetRepeaterItems();
        foreach($items as $item)
        {
            $total+=$item->ContainerManager->GetGrandTotal();
        }




        return $this->CreateCalculationObject($total,'',1);
    }
}