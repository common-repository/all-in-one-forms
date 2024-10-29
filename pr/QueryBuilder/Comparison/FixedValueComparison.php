<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\StringComparisonFormatter;

class FixedValueComparison extends ComparisonBase
{
    public $Table;
    public $Column;
    public $Value;
    /** @var ComparisonFormatterBase */
    public $ComparisonFormatter;
    public $ValueSubType='';
    /**
     * FixedValueComparison constructor.
     * @param $Table
     * @param $Column
     * @param $Comparison
     * @param $Value
     * @param $ComparisonFormatter ComparisonFormatterBase
     */
    public function __construct($Table, $Column, $Comparison, $Value,$ComparisonFormatter=null,$valueSubType='')
    {
        $this->Table = $Table;
        $this->Column = $Column;
        $this->Comparison = $Comparison;
        $this->Value = $Value;
        $this->ComparisonFormatter=$ComparisonFormatter;
        $this->ValueSubType=$valueSubType;

        if($this->ComparisonFormatter==null)
            $this->ComparisonFormatter=new StringComparisonFormatter();
    }


    public function CreateComparison()
    {
        return $this->MaybeAddSubType($this->CreateComparisonString($this->Table.'.'.($this->Column==''?'value':$this->Column),$this->ComparisonFormatter->Format($this->Value)));
    }

    private function MaybeAddSubType($comparison)
    {
        if($this->ValueSubType=='')
            return $comparison;

        global $wpdb;

        if($this->Comparison=='IsEmpty')
            return '(('.$this->Table.'.subtype is null or '.$this->Table.'.subtype='.$wpdb->prepare('%s',$this->ValueSubType).') and '.$comparison.')';

        return '('.$this->Table.'.subtype='.$wpdb->prepare('%s',$this->ValueSubType).' and '.$comparison.')';

    }
}