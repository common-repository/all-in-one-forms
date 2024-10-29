<?php 

namespace rednaoeasycalculationforms\DTO;

class ButtonCounterFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var Numeric */
	public $DefaultValue;
	/** @var string */
	public $MaxValue;
	/** @var string */
	public $MinValue;
	/** @var Numeric */
	public $Step;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='buttoncounter';
		$this->Label='Button Counter';
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->DefaultValue=0;
		$this->MinValue='';
		$this->MaxValue='';
		$this->Step=1;
	}
}

