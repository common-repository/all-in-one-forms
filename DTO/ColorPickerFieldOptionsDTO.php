<?php 

namespace rednaoeasycalculationforms\DTO;

class ColorPickerFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultColor;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$ColorPicker;
		$this->Label='Color Picker';
		$this->DefaultColor='';
		$this->PriceType=PriceTypeEnumDTO::$none;
	}
}

