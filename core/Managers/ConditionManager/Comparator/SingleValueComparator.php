<?php


namespace rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator;


class SingleValueComparator extends ComparatorBase
{

    public function Compare($ComparisonType, $Value)
    {
        $conditionValue=$this->Source->GetValue();
        switch ($ComparisonType)
        {
            case 'Equal':
                return $conditionValue==$Value;
                break;
            case 'NotEqual':
                return $conditionValue!=$Value;
                break;
            case 'IsEmpty':
                return $Value==null||$Value=='';
                break;
            case "IsNotEmpty":
                return $Value!=null&&$Value!='';
                break;

        }
    }
}