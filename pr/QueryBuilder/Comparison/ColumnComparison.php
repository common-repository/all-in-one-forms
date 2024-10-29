<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


class ColumnComparison extends ComparisonBase
{
    public $TableA;
    public $ColumnA;
    public $TableB;
    public $ColumnB;

    /**
     * ColumnComparison constructor.
     * @param $TableA
     * @param $ColumnA
     * @param $TableB
     * @param $ColumnB
     * @param $Comparison
     */
    public function __construct($TableA, $ColumnA,$Comparison, $TableB, $ColumnB )
    {
        $this->TableA = $TableA;
        $this->ColumnA = $ColumnA;
        $this->TableB = $TableB;
        $this->ColumnB = $ColumnB;
        $this->Comparison = $Comparison;
    }


    public function CreateComparison()
    {
        return $this->CreateComparisonString($this->TableA.'.'.$this->ColumnA,$this->TableB.'.'.$this->ColumnB);
    }
}