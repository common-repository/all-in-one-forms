<?php


namespace rednaoeasycalculationforms\DTO\core\Factories;


use rednaoeasycalculationforms\DTO\MustBeLoggedInRestrictionOptionsDTO;
use rednaoeasycalculationforms\DTO\NumberOfSubmissionsRestrictionOptionDTO;
use rednaoeasycalculationforms\DTO\RestrictionTypeEnumDTO;
use rednaoeasycalculationforms\DTO\UniqueRestrictionOptionDTO;

class RestrictionFactory
{
    public static function GetRestrictions($value)
    {
        $restrictionList=[];
        if(!is_array($value))
            return [];
        foreach($value as $currentValue)
        {
            if($currentValue->Type==RestrictionTypeEnumDTO::$Unique)
                $restrictionList[]=(new UniqueRestrictionOptionDTO())->Merge($currentValue);

            if($currentValue->Type==RestrictionTypeEnumDTO::$NumberOfSubmissions)
                $restrictionList[]=(new NumberOfSubmissionsRestrictionOptionDTO())->Merge($currentValue);

            if($currentValue->Type==RestrictionTypeEnumDTO::$MustBeLoggedIn)
                $restrictionList[]=(new MustBeLoggedInRestrictionOptionsDTO())->Merge($currentValue);
        }
        return $restrictionList;
    }
}