<?php 

namespace rednaoeasycalculationforms\DTO;

class URLConfirmationItemOptionsDTO extends ConfirmationItemBaseOptionsDTO{
	/** @var string */
	public $URL;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ConfirmationType=ConfirmationTypeEnumDTO::$URL;
		$this->URL='';
	}
}

