<?php 

namespace rednaoeasycalculationforms\DTO;

class RadioFieldOptionsDTO extends MultipleOptionsBaseOptionsDTO{
	/** @var string */
	public $Layout;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='Radio';
		$this->Type=FieldTypeEnumDTO::$Radio;
		$this->Layout='1';
	}
}

