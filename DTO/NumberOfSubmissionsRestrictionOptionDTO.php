<?php 

namespace rednaoeasycalculationforms\DTO;

class NumberOfSubmissionsRestrictionOptionDTO extends RestrictionBaseOptionsDTO{
	/** @var Numeric */
	public $NumberOfSubmissions;
	public $CountingType;
	public $ErrorMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=RestrictionTypeEnumDTO::$NumberOfSubmissions;
		$this->NumberOfSubmissions=0;
		$this->ErrorMessage=null;
		$this->CountingType='Total';
		$this->AddType("ErrorMessage","Object");
	}
}

