<?php 

namespace rednaoeasycalculationforms\DTO;

class SliderFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var Numeric */
	public $DefaultValue;
	/** @var Numeric */
	public $MaxValue;
	/** @var Numeric */
	public $MinValue;
	/** @var Numeric */
	public $Step;
	/** @var Boolean */
	public $ShowValueTooltip;
	public $ValueLabel;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Slider;
		$this->Label='Slider';
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->DefaultValue=0;
		$this->MinValue=0;
		$this->MaxValue=100;
		$this->Step=1;
		$this->ShowValueTooltip=true;
		$this->ValueLabel=null;
		$this->AddType("ValueLabel","Object");
	}
}

