<?php 

namespace rednaoeasycalculationforms\DTO;

class MaskedFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultText;
	/** @var string */
	public $Placeholder;
	public $MaskType;
	public $Mask;
	/** @var Boolean */
	public $ShowQuantitySelector;
	/** @var string */
	public $MaskChar;
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Masked;
		$this->Label='Mask';
		$this->MaskType=MaskEnumDTO::$Phone;
		$this->Mask='(000)00-000-0000';
		$this->MaskChar='_';
		$this->ShowQuantitySelector=false;
		$this->Placeholder='';
		$this->DefaultText='';
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->AddType("Icon","Object");
	}
}

