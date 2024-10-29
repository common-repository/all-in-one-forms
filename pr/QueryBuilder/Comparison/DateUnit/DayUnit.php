<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison\DateUnit;


use DateTime;

class DayUnit extends DateUnitBase
{

    public $Mode;

    /**
     * DayUnit constructor.
     */
    public function __construct($date,$mode='day')
    {
        parent::__construct($date);
        $this->Mode=$mode;
    }


    public function GetStartOfUnit()
    {
        return $this->Date->getTimestamp();
    }

    public function GetEndOfUnit()
    {
        $nextDate=new DateTime($this->Date->format('c'));

        $nextDate->modify('+1 '.$this->Mode);
        return $nextDate->getTimestamp();
    }
}