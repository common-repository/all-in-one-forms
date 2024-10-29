<?php


namespace rednaoeasycalculationforms\pr\QueryBuilder\Comparison;


class ListFieldFixedValueComparison extends FixedValueComparison
{
    public $TableName;
    public $FieldId;
    public $ValueSubType;
    public function __construct($TableId,$FieldId,$Table, $Column, $Comparison, $Value, $ComparisonFormatter = null,$ValueSubType='')
    {
        parent::__construct($TableId, $Column, $Comparison, $Value, $ComparisonFormatter);
        $this->TableName=$Table;
        $this->FieldId=$FieldId;
        $this->ValueSubType=$ValueSubType;

    }


    public function CreateComparison()
    {
        global $wpdb;

        switch ($this->Comparison)
        {
            case 'NotEqual':
                return $this->MaybeAddSubType($wpdb->prepare("( not exists(select 1 from ".$this->TableName.' aux where aux.entry_id=ROOT.entry_id and aux.field_id='.$wpdb->prepare('%s',$this->FieldId).
                    ' and aux.value = %s))',$this->Value));
            case 'NotContains':
                return $this->MaybeAddSubType("( not exists(select 1 from ".$this->TableName.' aux where aux.entry_id=ROOT.entry_id and aux.field_id='.$wpdb->prepare('%s',$this->FieldId).
                    " and aux.value like '%".$wpdb->esc_like($this->Value)."%'))");

        }

        return parent::CreateComparison();
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