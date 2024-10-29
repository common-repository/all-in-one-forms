<?php 

namespace rednaoeasycalculationforms\DTO;

class ColorSwatcherFieldOptionsDTO extends MultipleOptionsBaseOptionsDTO{
	/** @var ColorItemOptionsDTO[] */
	public $Options;
	/** @var Boolean */
	public $AllowMultipleSelection;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$ColorSwatcher;
		$this->Label='Color Swatcher';
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->AllowMultipleSelection=false;
		$this->Options=[];
		$this->AddType("Options","ColorItemOptionsDTO");
	}
}

