<?php 

namespace rednaoeasycalculationforms\DTO;

class SurveyColumnOptionsDTO extends ItemOptionsDTO{
	/** @var string */
	public $Width;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Width='';
	}
}

