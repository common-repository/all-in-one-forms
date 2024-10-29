<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


class MultipleValueComparison extends FixedValueComparison
{
    public $TableId;
    public $FieldId;
    public $ValueSubType;
    public function __construct($TableId,$FieldId,$Table, $Column, $Comparison, $Value, $ComparisonFormatter = null,$ValueSubType='')
    {
        parent::__construct($Table, $Column, $Comparison, $Value, $ComparisonFormatter);
        $this->TableId=$TableId;
        $this->FieldId=$FieldId;
        $this->ValueSubType=$ValueSubType;

    }


    public function CreateComparison()
    {
        $value=$this->Value;
        $escapedValuesArray=array();
        global $wpdb;
        if(\is_array($value))
        {
            foreach($value as $currentValue)
            {
                $escapedValuesArray[]=$wpdb->prepare('%s',$currentValue);
            }
        }


        $leftSide=$this->TableId.'.'.($this->Column==''?'value':$this->Column);
        global $wpdb;

        switch ($this->Comparison)
        {
            case 'Contains':
                return $this->MaybeAddSubType($leftSide.' in ('.\implode(',',$escapedValuesArray).')');
            case 'NotContains':
                return $this->MaybeAddSubType("( not exists(select 1 from ".$this->Table.' aux where aux.entry_id=ROOT.entry_id and aux.field_id='.$wpdb->prepare('%s',$this->FieldId).
                    ' and aux.value in ('.\implode(',',$escapedValuesArray).')))');
            case 'IsEmpty':
                return $this->MaybeAddSubType($leftSide .' is null ');
            case 'IsNotEmpty':
                return $this->MaybeAddSubType($leftSide .' is not null ');

        }

        return parent::CreateComparisonString($leftSide,$this->ComparisonFormatter->Format($this->Value));
    }

    private function MaybeAddSubType($comparison)
    {
        if($this->ValueSubType=='')
            return $comparison;

        global $wpdb;

        if($this->Comparison=='IsEmpty')
            return '(('.$this->TableId.'.subtype is null or '.$this->TableId.'.subtype='.$wpdb->prepare('%s',$this->ValueSubType).') and '.$comparison.')';

        return '('.$this->TableId.'.subtype='.$wpdb->prepare('%s',$this->ValueSubType).' and '.$comparison.')';

    }
}