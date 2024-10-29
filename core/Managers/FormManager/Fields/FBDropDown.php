<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\SingleValueComparator;

class FBDropDown extends FBMultipleOptionsField
{
    protected function CanSanitize()
    {
        return true;
    }
}