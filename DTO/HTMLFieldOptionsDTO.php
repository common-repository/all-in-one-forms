<?php 

namespace rednaoeasycalculationforms\DTO;

class HTMLFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $HTML;
	public $TextPosition;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$HTML;
		$this->Label='HTML';
		$this->HTML=null;
		$this->TextPosition='Bottom';
		$this->AddType("HTML","Object");
	}
}

