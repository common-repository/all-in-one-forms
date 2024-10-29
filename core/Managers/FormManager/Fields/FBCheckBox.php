<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\SingleValueComparator;

class FBCheckBox extends FBMultipleOptionsField
{
    protected function CanSanitize()
    {
        return true;
    }
}