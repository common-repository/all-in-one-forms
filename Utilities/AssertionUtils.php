<?php

namespace rednaoeasycalculationforms\Utilities;

class AssertionUtils
{
    public static function ValueIsEqual($value1,$value2)
    {
        if($value1==null&&$value2==null)
            return true;
        if($value1==null&&$value2!=null)
            return false;
        if($value1!=null&&$value2==null)
            return false;

        if(is_array($value1)&&is_array($value2))
        {
            $value1Keys=array_keys($value1);
            $value2Keys=array_keys($value2);

            $propertiesThatEndsWithIgnored=['ValuesLoaded','TypeDictionary'];
            $value1=array_filter($value1,function($key) use ($propertiesThatEndsWithIgnored){
                foreach ($propertiesThatEndsWithIgnored as $property)
                {
                    if(str_ends_with($key,$property))
                        return false;
                }
                return true;
            },ARRAY_FILTER_USE_KEY);

            $value2=array_filter($value2,function($key) use ($propertiesThatEndsWithIgnored){
                foreach ($propertiesThatEndsWithIgnored as $property)
                {
                    if(str_ends_with($key,$property))
                        return false;
                }
                return true;
            },ARRAY_FILTER_USE_KEY);
            if(count($value1)!=count($value2))
                return false;

            foreach ($value1 as $key=>$value)
            {
                if(!array_key_exists($key,$value2))
                    return false;
                if(!self::ValueIsEqual($value,$value2[$key]))
                    return false;
            }

            return true;
        }

        if(is_object($value1)&&is_object($value2))
        {
            $value1=(array)$value1;
            $value2=(array)$value2;



            return self::ValueIsEqual($value1,$value2);
        }

        return $value1==$value2;

    }

    public static function ValueIsEqualByProperties($value1,$value2,$propertiesToCheck=[])
    {


    }
}