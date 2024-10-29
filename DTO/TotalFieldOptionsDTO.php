<?php 

namespace rednaoeasycalculationforms\DTO;

class TotalFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Text;
	public $TextPosition;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Total;
		$this->Label='Total';
		$this->Text=null;
		$this->TextPosition='Bottom';
		$this->AddType("Text","Object");
	}
}

