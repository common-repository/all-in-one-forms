<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison\DateUnit;


use DateTime;

abstract class DateUnitBase
{
    /** @var DateTime */
    public $Date;
    public function __construct($date)
    {
        if($date==null)
            $date=0;
        $this->Date=new DateTime(\date('Y-m-d',$date));
        $this->InitializeDate();
    }
    
    

    public abstract function GetStartOfUnit();
    public abstract function GetEndOfUnit();

    private function InitializeDate()
    {
    }


}