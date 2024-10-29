<?php

namespace rednaoeasycalculationforms\Parser\Core;

use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBFieldBase;

class ParseUtilities
{
    public static function SanitizeNumber($val,$defaultValue)
    {
        if($val==null)
            return $defaultValue;

        if($val instanceof FBFieldBase)
            return $val->ToNumber();

        if(!is_numeric($val))
            return $defaultValue;

        return floatval($val);
    }

    /**
     * @param $element ParseBase
     * @return void
     */
    public static function NotifyReturnToParent($element)
    {
        while(!in_array($element->Parent->Options->T,['BLO','MA','FOR','FE']))
        {
            $element=$element->Parent;
        }
        $element->Parent->ReturnWasExecuted();
    }

    public static function NotifyBreakToParent($element)
    {
        while(!in_array($element->Parent->Options->T,['FOR','FE']))
        {
            $element=$element->Parent;
        }
        $element->Parent->BreakWasExecuted();
    }

    public static function IsNumeric($val)
    {
        return is_int($val) || is_float($val);
    }
}