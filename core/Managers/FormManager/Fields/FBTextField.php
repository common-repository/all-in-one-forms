<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\SingleValueComparator;

class FBTextField extends FBFieldWithPrice
{
    public function GetComparator()
    {
        return new SingleValueComparator($this->GetForm(),$this);
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