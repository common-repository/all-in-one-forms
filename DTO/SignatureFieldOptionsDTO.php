<?php 

namespace rednaoeasycalculationforms\DTO;

class SignatureFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultText;
	/** @var Boolean */
	public $ShowQuantitySelector;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Signature;
		$this->Label='Signature';
		$this->PriceType=PriceTypeEnumDTO::$none;
	}
}

