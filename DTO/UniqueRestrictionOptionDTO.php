<?php 

namespace rednaoeasycalculationforms\DTO;

class UniqueRestrictionOptionDTO extends RestrictionBaseOptionsDTO{
	/** @var Numeric */
	public $FieldId;
	/** @var string */
	public $NumberOfTimes;
	public $ErrorMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=RestrictionTypeEnumDTO::$Unique;
		$this->FieldId=0;
		$this->NumberOfTimes='';
		$this->ErrorMessage=null;
		$this->AddType("ErrorMessage","Object");
	}
}

