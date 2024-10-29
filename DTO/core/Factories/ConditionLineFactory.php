<?php

namespace rednaoeasycalculationforms\DTO\core\Factories;

use rednaoeasycalculationforms\DTO\SubTypeEnumDTO;

class ConditionLineFactory
{
    public static function GetValue($conditionLineOptions,$value)
    {
        if ($conditionLineOptions->SubType == SubTypeEnumDTO::$MultipleValues) {
            if ($value == '' || !is_array($value))
                return [];
            return $value;
        }
    }
}