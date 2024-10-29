<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;

use rednaoeasycalculationforms\core\Managers\FormManager\Calculator\CalculatorFactory;
use rednaoeasycalculationforms\DTO\FieldWithPriceOptionsDTO;

abstract class FBFieldWithPrice extends FBFieldBase
{
    /** @var FieldWithPriceOptionsDTO */
    public $Options;
    public function GetPriceWithoutFormula(){

    }


    public function GetRegularPrice(){
        return trim($this->Options->Price);
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        $calculator=CalculatorFactory::GetCalculator($this);
        $calculator->Sanitize($sanitizer);
    }


}
