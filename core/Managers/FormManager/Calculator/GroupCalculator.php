<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBGroupPanel;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class GroupCalculator extends CalculatorBase
{
    public $Total;
    /** @var FBGroupPanel */
    public $Field;
    public $OptionsUnitPrice;

    public function ExecutedCalculation($value)
    {
        if($this->Field->Entry==null)
            return $this->CreateCalculationObject('','',0);

        $unitPrice=$this->Field->ContainerManager->GetUnitPrice();
        $quantity=$this->Field->ContainerManager->GetQuantity();

        return $this->CreateCalculationObject($unitPrice,'',$quantity);
    }
}