<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldWithPrice;

class FBHiddenField extends FBFieldWithPrice
{
    public function GetValue()
    {
        return $this->GetEntryValue('Value','');
    }


    protected function CanSanitize()
    {
        return true;
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer);
        $sanitizer->AddStringSanitizer('Value');
    }
}