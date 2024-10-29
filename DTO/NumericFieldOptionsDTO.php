<?php 

namespace rednaoeasycalculationforms\DTO;

class NumericFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultValue;
	/** @var string */
	public $Placeholder;
	public $FreeCharOrWords;
	/** @var Boolean */
	public $IgnoreSpaces;
	/** @var Numeric */
	public $NumberOfDecimals;
	/** @var string */
	public $MinimumValue;
	/** @var string */
	public $MaximumValue;
	public $Icon;
	/** @var Boolean */
	public $ReadOnly;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Numeric;
		$this->Label='Numeric';
		$this->ReadOnly=false;
		$this->NumberOfDecimals=2;
		$this->MinimumValue='';
		$this->MaximumValue='';
		$this->IgnoreSpaces=false;
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Placeholder='';
		$this->DefaultValue='';
		$this->FreeCharOrWords=0;
		$this->AddType("Icon","Object");
	}
}

