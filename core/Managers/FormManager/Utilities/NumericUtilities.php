<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Utilities;


class NumericUtilities
{
    public static function ParseNumber($price,$defaultValue=0)
    {
        if($price=='')
            return $defaultValue;

        return \floatval($price);
    }
}