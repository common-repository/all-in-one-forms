<?php 

namespace rednaoeasycalculationforms\DTO;

class SwitchFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var Boolean */
	public $Checked;
	/** @var string */
	public $CheckedText;
	/** @var string */
	public $UnCheckedText;
	/** @var string */
	public $Style;
	/** @var string */
	public $CheckColor;
	/** @var string */
	public $UnCheckColor;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Switch;
		$this->Label='Switch';
		$this->Style='rounded';
		$this->CheckColor='#080';
		$this->UnCheckColor='#888';
		$this->Checked=false;
		$this->CheckedText='Yes';
		$this->UnCheckedText='No';
		$this->PriceType=PriceTypeEnumDTO::$none;
	}
}

