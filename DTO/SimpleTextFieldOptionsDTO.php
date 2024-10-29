<?php 

namespace rednaoeasycalculationforms\DTO;

class SimpleTextFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Text;
	public $TextPosition;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$SimpleText;
		$this->Label='Simple text box';
		$this->Text=null;
		$this->TextPosition='Bottom';
		$this->AddType("Text","Object");
	}
}

