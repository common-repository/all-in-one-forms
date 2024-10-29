<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\ComparisonFormatterBase;
use rednaoeasycalculationforms\pr\QueryBuilder\Comparison\ComparisonFormatter\StringComparisonFormatter;

class CheckBoxComparison extends ComparisonBase
{
    public $Table;
    public $Column;
    public $Value;
    /** @var ComparisonFormatterBase */
    public $ComparisonFormatter;
    /**
     * FixedValueComparison constructor.
     * @param $Table
     * @param $Column
     * @param $Comparison
     * @param $Value
     * @param $ComparisonFormatter ComparisonFormatterBase
     */
    public function __construct($Table, $Column, $Comparison, $Value,$ComparisonFormatter=null)
    {
        $this->Table = $Table;
        $this->Column = $Column;
        $this->Comparison = $Comparison;
        $this->Value = $Value;
        $this->ComparisonFormatter=$ComparisonFormatter;

        if($this->ComparisonFormatter==null)
            $this->ComparisonFormatter=new StringComparisonFormatter();
    }


    public function CreateComparison()
    {
        $leftSide=$this->Table.'.'.($this->Column==''?'value':$this->Column);
        switch ($this->Comparison)
        {
            case 'IsChecked':
                return $leftSide.'= 1';
            case 'IsNotChecked':
                return $leftSide.' is null';
        }
    }
}