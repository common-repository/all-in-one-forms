<?php


namespace rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator;


class MultipleValueComparator extends ComparatorBase
{

    public function Compare($ComparisonType, $Value)
    {
        if($Value==null)
            $Value=array();

        if(!\is_array($Value))
            $Value=[$Value];

        $selectedValues=$this->Source->GetValue();
        if(!\is_array($selectedValues))
            $selectedValues=[$selectedValues];

        $selectedValues=\array_map(function ($x){return $x->Id;},$selectedValues);
        if(!\is_array($selectedValues))
            $selectedValues=[$selectedValues];

        switch ($ComparisonType)
        {
            case 'Contains':
            case 'ChangedTo':
            case "ChangedFrom":
                return !empty(\array_intersect($Value,$selectedValues));
                break;
            case "NotContains":
                return empty(\array_intersect($Value,$selectedValues));
                break;
            case "IsEmpty":
                return count($Value)==0;
                break;
            case "IsNotEmpty":
                return count($Value)>0;
                break;
        }

    }
}