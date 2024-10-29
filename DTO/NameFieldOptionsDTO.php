<?php 

namespace rednaoeasycalculationforms\DTO;

class NameFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultText;
	public $Format;
	/** @var string */
	public $FirstNameLabel;
	/** @var string */
	public $LastNameLabel;
	/** @var string */
	public $FirstNameDefaultText;
	/** @var string */
	public $LastNameDefaultText;
	/** @var string */
	public $FirstNamePlaceholder;
	/** @var string */
	public $LastNamePlaceholder;
	/** @var IconOptionsDTO */
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='Name';
		$this->Type=FieldTypeEnumDTO::$Name;
		$this->Format=NameFormatEnumDTO::$FirstAndLast;
		$this->FirstNameLabel='First Name';
		$this->LastNameLabel='Last Name';
		$this->FirstNameDefaultText='';
		$this->LastNameDefaultText='';
		$this->FirstNamePlaceholder='';
		$this->LastNamePlaceholder='';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->AddType("Icon","IconOptionsDTO");
	}
}

