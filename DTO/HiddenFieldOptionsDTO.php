<?php 

namespace rednaoeasycalculationforms\DTO;

class HiddenFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $Value;
	/** @var string */
	public $Label;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Value='';
		$this->Label='Hidden';
		$this->Type=FieldTypeEnumDTO::$Hidden;
	}
}

