<?php 

namespace rednaoeasycalculationforms\DTO;

class ActionFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $Label;
	public $Icon;
	public $Action;
	/** @var Boolean */
	public $HideSubmitButton;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$ActionButton;
		$this->Action=ButtonActionEnumDTO::$None;
		$this->Label='Click';
		$this->HideSubmitButton=false;
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->AddType("Icon","Object");
	}
}

