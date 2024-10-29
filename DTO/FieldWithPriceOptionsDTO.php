<?php 

namespace rednaoeasycalculationforms\DTO;

class FieldWithPriceOptionsDTO extends FieldBaseOptionsDTO{
	public $PriceType;
	/** @var string */
	public $Price;
	/** @var string */
	public $SalePrice;
	/** @var Boolean */
	public $HidePrice;
	/** @var Boolean */
	public $ShowQuantitySelector;
	public $QuantityPosition;
	public $QuantityMinimumValue;
	public $QuantityMaximumValue;
	public $QuantityDefaultValue;
	public $QuantityPlaceholder;
	public $QuantityLabel;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->Price='';
		$this->SalePrice='';
		$this->HidePrice=false;
		$this->ShowQuantitySelector=false;
		$this->QuantityPosition='top';
		$this->QuantityMinimumValue=0;
		$this->QuantityMaximumValue=0;
		$this->QuantityDefaultValue='';
		$this->QuantityPlaceholder='';
		$this->QuantityLabel='';
	}
}

