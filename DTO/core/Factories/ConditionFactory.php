<?php


namespace rednaoeasycalculationforms\DTO\core\Factories;


use rednaoeasycalculationforms\DTO\ShowHideConditionOptionsDTO;
use rednaoeasycalculationforms\DTO\ShowHideStepConditionOptionsDTO;
use rednaoeasycalculationforms\DTO\SkipRepeaterItemConditionOptionsDTO;
use rednaoeasycalculationforms\DTO\ValidationConditionOptionsDTO;

class ConditionFactory
{
    public static function GetConditions($value)
    {
        $newValue = [];
        foreach ($value as $currentCondition) {
            switch ($currentCondition->Type)
            {
                case 'ShowHide':
                    $newValue[]=(new ShowHideConditionOptionsDTO())->Merge($currentCondition);
                    break;
                case 'Validation':
                    $newValue[]=(new ValidationConditionOptionsDTO())->Merge($currentCondition);
                    break;
                case 'ShowHideStep':
                    $newValue[]=(new ShowHideStepConditionOptionsDTO())->Merge($currentCondition);
                    break;
                case 'SkipRepeaterItem':
                    $newValue[]=(new SkipRepeaterItemConditionOptionsDTO())->Merge($currentCondition);
                    break;
                default:
                    $newValue[]=$currentCondition;
            }
        }

        return $newValue;
    }

}