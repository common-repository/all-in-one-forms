<?php


namespace rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator;


interface ComparisonSource
{
    public function GetValue();
    public function IsUsed();
}