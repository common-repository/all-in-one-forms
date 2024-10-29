<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use Exception;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBMultipleOptionsField;
use rednaoeasycalculationforms\core\Managers\FormManager\Utilities\NumericUtilities;

class PricePerItemCalculator extends CalculatorBase
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

        $numberOfItems=\count($value);

        $regularPriceToUse=$this->CustomRegularPrice!=null?$this->CustomRegularPrice:$this->Field->GetRegularPrice();


        $priceItem=0;
        $saleItem=0;
        $regularPriceItem=0;

        if($regularPriceToUse!='')
        {
            $regularPriceItem=\floatval($regularPriceToUse);
            $priceItem=$regularPriceItem;
            $regularPriceToUse *= $numberOfItems;
        }



        foreach($value as $currentItem)
        {
            if(!isset($currentItem->total))
            {
                throw new Exception('Total of item was not defined');
            }

            if($currentItem->total->Quantity!=0)
                throw new Exception('Quantity of item does not match');

            if($currentItem->total->RegularPrice!=$regularPriceItem)
                throw new Exception('Regular price of item does not match');

            if($currentItem->total->SalePrice!=$saleItem)
                throw new Exception('Sale price of item does not match');

            if($currentItem->total->Price!=$priceItem)
                throw new Exception('Price of item does not match');
        }




        if((\is_array($value)&&count($value)>0) ||(!\is_array($value)&& \strlen($value)>0))
        {
            return $this->CreateCalculationObject($regularPriceToUse,'',0);
        }else
            return $this->CreateCalculationObject('','',0);
    }

}