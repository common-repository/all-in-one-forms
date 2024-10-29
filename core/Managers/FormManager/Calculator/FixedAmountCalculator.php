<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


class FixedAmountCalculator extends CalculatorBase
{
    private $CustomSalePrice;
    private $CustomRegularPrice;

    public function __construct($field)
    {
        parent::__construct($field);
        $this->CustomRegularPrice=null;
        $this->CustomSalePrice=null;
    }


    public function SetRegularPrice($price)
    {
        $this->CustomRegularPrice=$price;
        return $this;
    }

    public function SetSalePrice($price){
        $this->CustomSalePrice=$price;
        return $this;
    }


    public function ExecutedCalculation($value)
    {
        if($value==null)
            $value=$this->Field->GetValue();

        $regularPriceToUse=$this->CustomRegularPrice!==null?$this->CustomRegularPrice:$this->Field->GetRegularPrice();

        if($this->Field->IsUsed())
        {
            return $this->CreateCalculationObject($regularPriceToUse,'',1);
        }else
            return $this->CreateCalculationObject('','',0);
    }


}