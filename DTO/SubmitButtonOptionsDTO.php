<?php 

namespace rednaoeasycalculationforms\DTO;

class SubmitButtonOptionsDTO extends FieldBaseOptionsDTO{
	/** @var IconOptionsDTO */
	public $Icon;
	/** @var Boolean */
	public $Hide;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$SubmitButton;
		$this->Label='Submit';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Hide=false;
	}
}

