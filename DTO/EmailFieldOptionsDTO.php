<?php 

namespace rednaoeasycalculationforms\DTO;

class EmailFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultText;
	/** @var string */
	public $Placeholder;
	/** @var Boolean */
	public $ReadOnly;
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ReadOnly=false;
		$this->Type=FieldTypeEnumDTO::$Email;
		$this->Label='Email';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Placeholder='';
		$this->DefaultText='';
		$this->AddType("Icon","Object");
	}
}

