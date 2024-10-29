<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


use DateTime;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\DateComparisonFormatter;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\DateUnit\DayUnit;

class DateFixedValueComparison extends FixedValueComparison
{

    public function __construct($Table, $Column, $Comparison, $Value, $ComparisonFormatter = null)
    {
        if ($ComparisonFormatter == null)
            $ComparisonFormatter = new DateComparisonFormatter();
        parent::__construct($Table, $Column, $Comparison, $Value, $ComparisonFormatter);
    }

    public function CreateComparison()
    {
        $value = $this->Value;

        if($this->Column=='')
            $this->Column='datevalue';
        $leftSide = $this->Table . '.' . $this->Column;




        $today = new DateTime(date('Y-m-d', \current_time('timestamp')));

        switch ($this->Value)
        {
            case 'today':
                $value = new DayUnit($today->getTimestamp());
                break;
            case 'startofweek':
                $value = new DayUnit(strtotime('last sunday', $today->getTimestamp()));
                break;
            case 'endofweek':
                $value = new DayUnit(strtotime('next sunday', $today->getTimestamp()));
                break;
            case 'startofmonth':
                $value = new DayUnit(strtotime('first day of this month', $today->getTimestamp()));
                break;
            case 'endofmonth':
                $value = new DayUnit(strtotime('last day of this month', $today->getTimestamp()));
                break;
            case 'xDaysFromNow':
                $options = $value->Value->AdditionalOptions;
                $numberOfUnits = $options->NumberOfUnits;
                if ($numberOfUnits > 0)
                    $numberOfUnits = '+' . $numberOfUnits;
                $value = new DayUnit(strtotime($numberOfUnits . ' days'));
                break;

            case 'xWeeksFromNow':
                $options = $value->Value->AdditionalOptions;
                $numberOfUnits = $options->NumberOfUnits;
                if ($numberOfUnits > 0)
                    $numberOfUnits = '+' . $numberOfUnits;
                $value = new DayUnit(strtotime($numberOfUnits . ' weeks'));
                break;

            case 'xMonthsFromNow':
                $options = $value->Value->AdditionalOptions;
                $numberOfUnits = $options->NumberOfUnits;
                if ($numberOfUnits > 0)
                    $numberOfUnits = '+' . $numberOfUnits;
                $value = new DayUnit(strtotime($numberOfUnits . ' months'));
                break;
            default:
                $value=$this->Value;
                if(!is_numeric($this->Value))
                    $value=strtotime($this->Value);
                $value=new DayUnit($value);
        }


        switch ($this->Comparison)
        {
            case 'Equal':
                return " (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($value->GetStartOfUnit()) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit()) . ')';
            case 'NotEqual':
                return " ( ".$leftSide." is null or not (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($value->GetStartOfUnit()) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit()) . '))';
            case 'GreaterThan':
                return $leftSide . ' > ' . $this->ComparisonFormatter->Format($value->GetStartOfUnit());
            case 'GreaterOrEqualThan':
                return $leftSide . ' >= ' . $this->ComparisonFormatter->Format($value->GetStartOfUnit());
            case 'LessThan':
                return $leftSide . ' < ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit());
            case 'LessOrEqualThan':
                return $leftSide . ' <= ' . $this->ComparisonFormatter->Format($value->GetEndOfUnit());

        }

        return parent::CreateComparisonString($leftSide, $this->ComparisonFormatter->Format($value->GetStartOfUnit()));
        /*
                if($value->Type=='Relative')
                {
                    $startDate=null;
                    $endDate=null;


                    switch ($value->Value->Id)
                    {
                        case 'today':
                            $startDate=$today;
                            $endDate=new DateTime($startDate->format('c'));
                            $endDate->modify('+1 day');

                            return " (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($startDate->getTimestamp()) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($endDate->getTimestamp()) . ')';

                        case 'startofweek':

                            $startDate=\strtotime('last sunday', $today->getTimestamp());
                            $endDate=\strtotime('next sunday', $today->getTimestamp());
                            return " (" . $leftSide . '>=' . $this->ComparisonFormatter->Format($startDate) . ' && ' . $leftSide . ' < ' . $this->ComparisonFormatter->Format($endDate) . ')';

                    }

                }*/


    }


}