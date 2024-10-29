<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\FormManager\Utilities\NumericUtilities;
use rednaoeasycalculationforms\core\Managers\SerializationManager\SanitizationManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;

abstract class CalculatorBase
{
    public $RegularPrice;
    public $SalePrice;
    public $IsSale;
    public $IsUsed;
    public $Quantity;
    public $Price;
    /** @var FBFieldBase */
    public $Field;
    public function __construct($field)
    {
        $this->Price=null;
        $this->SalePrice=null;
        $this->RegularPrice=null;
        $this->Field=$field;
        $this->Quantity=0;
    }

    public function UpdatePrice($newPrice,$newSale,$newQuantity)
    {
        $this->Quantity=$newQuantity;

        if($newPrice==''&&$newSale=='')
        {
            $this->Price=0;
            $this->IsUsed=false;
            $this->SalePrice=0;
        }else{
            $this->IsUsed=true;
            $price=NumericUtilities::ParseNumber($newPrice,0);
            if($newSale!='')
            {
                $this->IsUsed=true;
                $salePrice=NumericUtilities::ParseNumber($newSale,0);
                $this->Price=$salePrice;
                $this->SalePrice=$salePrice;
                $this->RegularPrice=$price;
            }else{
                $this->IsUsed=true;
                $this->Price=$price;
                $this->SalePrice=0;
                $this->RegularPrice=$price;
            }

        }

        if($this->Quantity>0)
            $this->IsUsed=true;

    }

    public function GetIsSale(){
        return $this->IsSale;
    }

    public function GetPrice(){
        if($this->IsUsed)
            return $this->Price;
        return 0;
    }

    public function GetQuantity(){
        if($this->Field->IsUsed())
            return max(1,Sanitizer::GetNumberValueFromPath($this,['Field','Entry','Quantity']));
        else
            return 0;
    }

    public function GetIsValid(){

        if($this->Field->Entry==null)
            return true;

        $result=$this->ExecutedCalculation(null);

        $frontPrice=Sanitizer::SanitizeNumber(Sanitizer::GetStringValueFromPath($this->Field,['Entry','UnitPrice']));
        $frontQuantity=Sanitizer::SanitizeNumber(Sanitizer::GetStringValueFromPath($this->Field,['Entry','Quantity']));

        $serverPrice=Sanitizer::SanitizeNumber(Sanitizer::GetStringValueFromPath($result,['RegularPrice']));
        $serverQuantity=Sanitizer::SanitizeNumber(Sanitizer::GetStringValueFromPath($result,['Quantity']));


        return $frontPrice==$serverPrice&&$frontQuantity==$serverQuantity;


    }


    public function CanCalculateQuantity(){
        return false;
    }

    public function CreateCalculationObject($regularPrice,$salePrice,$quantity)
    {
        return array(
            'Quantity'=>$quantity,
            'RegularPrice'=>$regularPrice,
            'SalePrice'=>$salePrice
        );
    }

    public function GetDependsOnOtherFields(){
        return false;
    }

    public function ExecuteAndUpdate(){
        $price=$this->ExecutedCalculation(null);
    //    $this->UpdatePrice($price['RegularPrice'],$price['SalePrice'],$price['Quantity']);

    }



    public function Sanitize($sanitizer)
    {
        $sanitizer->AddStringSanitizer('PriceType');
        $sanitizer->AddNumericSanitizer('Price');
        $sanitizer->AddNumericSanitizer('Quantity');
        $sanitizer->AddNumericSanitizer('UnitPrice');
    }
    public abstract function ExecutedCalculation($value);
}