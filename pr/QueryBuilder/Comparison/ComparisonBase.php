<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


use rednaoeasycalculationforms\core\Exception\FriendlyException;

abstract class ComparisonBase
{
    public abstract function CreateComparison();
    public $Comparison;


    public function CreateComparisonString($leftSide,$rightSide)
    {
        switch ($this->Comparison)
        {
            case 'Equal':
                return $leftSide.' = '.$rightSide;
            case 'NotEqual':
                return '('.$leftSide.' <> '.$rightSide.' or '.$leftSide.' is null)';
            case 'GreaterThan':
                return $leftSide.' > '.$rightSide;
            case 'GreaterOrEqualThan':
                return $leftSide.' >= '.$rightSide;
            case 'LessThan':
                return $leftSide.' < '.$rightSide;
            case 'LessOrEqualThan':
                return $leftSide.' <= '.$rightSide;
            case 'IsEmpty':
                return "(". $leftSide.' = "" || '. $leftSide.' is null)';
            case 'IsNotEmpty':
                return "(". $leftSide.' <> "" && '. $leftSide.' is not null)';
            case 'Contains':
                return $leftSide." like concat('%',".$rightSide.",'%') ";
            case 'IsNull':
                return $leftSide.' is null';
            case 'NotContains':
                return "(".$leftSide ." is null || ".$leftSide." not like concat('%',".$rightSide.",'%') )";
            default:
                throw new FriendlyException('Invalid comparison type '.$this->Comparison);
        }

    }




}