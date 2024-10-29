<?php 

namespace rednaoeasycalculationforms\DTO;

class DropdownFieldOptionsDTO extends MultipleOptionsBaseOptionsDTO{
	/** @var IconOptionsDTO */
	public $Icon;
	/** @var string */
	public $Placeholder;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='Dropdown';
		$this->Placeholder='Select a value';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Type=FieldTypeEnumDTO::$DropDown;
	}
}

