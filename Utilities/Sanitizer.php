<?php


namespace rednaoeasycalculationforms\Utilities;


class Sanitizer
{
    public static function SanitizeString($value,$defaultValue='')
    {
        if($value==null)
            return $defaultValue;

        if(is_array($value))
            return $defaultValue;
        if(is_object($value))
            return $defaultValue;

        return strval($value);
    }

    public static function SanitizeHTMLSize($size,$ifNumberAppend='px')
    {
        $size=trim($size);
        if(preg_match('/\d+(\\.?)\d*(px|%)?/',$size))
        {
            if(!str_ends_with(strtolower($size),'px')&&!str_ends_with($size,'%'))
                $size.='px';

            return $size;
        }

        return '';

    }

    public static function SanitizeWithRegex($value,$regex,$defaultValue='')
    {
        if(!is_string($value))
            return $defaultValue;
        if(!preg_match($regex,$value))
            return $defaultValue;

        return $value;
    }

    public static function SanitizeSTDClass($value)
    {
        if($value==null)
            return null;

        if(is_array($value))
            return (object)$value;

        if(is_object($value))
            return $value;

        return null;


    }

    public static function SanitizeNumber($value,$defaultValue=0)
    {
        if($value==null||!is_numeric($value))
            return $defaultValue;

        return floatval($value);

    }

    public static function SanitizeArray($value,$convertToArrayIfPossible=false)
    {
        if($value==null)
            return [];

        if(is_array($value))
            return $value;

        if(is_scalar($value))
        {
            if ($convertToArrayIfPossible)
                return [$value];
            else
                return [];
        }

        return [];






    }

    public static function SanitizeBoolean($value,$defaultValue=false)
    {
        if($value===null)
            return $defaultValue;

        if(is_bool($value))
            return $value;

        return $defaultValue;

    }

    public static function GetStringValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeString(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetBooleanValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeBoolean(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetNumberValueFromPath($value, $path,$defaultValue=null)
    {
        return Sanitizer::SanitizeNumber(Sanitizer::GetValueFromPath($value,$path,$defaultValue));
    }

    public static function GetValueFromPath($obj, $path, $defaultValue=null)
    {
        if(!is_array($path))
            $path=[$path];
        if($obj==null)
            return $defaultValue;

        if(is_array($obj))
            $obj=(object)$obj;

        while($currentPath=array_shift($path))
        {
            if(isset($obj->{$currentPath}))
            {
                $obj=$obj->{$currentPath};
                if(Sanitizer::IsAssoc($obj))
                    $obj=(object)$obj;
            }else
                return $defaultValue;
        }

        return $obj;
    }

    public static function IsAssoc($arr)
    {
        if (!is_array($arr)) return false;
        if(count(array_keys($arr))==0)
            return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }


    public static function PathExist($obj,$path)
    {
        if(!is_array($path))
            $path=[$path];
        if($obj==null)
            return null;

        if(is_array($obj))
            $obj=(object)$obj;

        while($currentPath=array_shift($path))
        {
            if(isset($obj->{$currentPath}))
            {
                $obj=$obj->{$currentPath};
                if(is_array($obj))
                    $obj=(object)$obj;
            }else
                return false;
        }

        return true;

    }

}