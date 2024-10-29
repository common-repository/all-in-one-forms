<?php 

namespace rednaoeasycalculationforms\DTO;

class RecaptchaFieldOptionsDTO extends FieldWithPriceOptionsDTO{


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Recaptcha;
		$this->Label='Recaptcha';
		$this->Required=true;
	}
}

