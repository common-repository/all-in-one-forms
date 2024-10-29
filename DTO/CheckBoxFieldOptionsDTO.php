<?php 

namespace rednaoeasycalculationforms\DTO;

class CheckBoxFieldOptionsDTO extends MultipleOptionsBaseOptionsDTO{
	/** @var string */
	public $Layout;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='Checkbox';
		$this->Type=FieldTypeEnumDTO::$Checkbox;
		$this->Layout='1';
	}
}

