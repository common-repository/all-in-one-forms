<?php 

namespace rednaoeasycalculationforms\DTO;

class DividerFieldOptionsDTO extends FieldBaseOptionsDTO{
	/** @var string */
	public $Style;
	/** @var string */
	public $Color;
	public $Title;
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Title=null;
		$this->Type=FieldTypeEnumDTO::$Divider;
		$this->Style='solid';
		$this->Color='#dfdfdf';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->AddType("Title","Object");
		$this->AddType("Icon","Object");
	}
}

