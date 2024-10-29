<?php 

namespace rednaoeasycalculationforms\DTO;

class MustBeLoggedInRestrictionOptionsDTO extends RestrictionBaseOptionsDTO{
	public $ErrorMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=RestrictionTypeEnumDTO::$MustBeLoggedIn;
		$this->ErrorMessage=null;
		$this->AddType("ErrorMessage","Object");
	}
}

