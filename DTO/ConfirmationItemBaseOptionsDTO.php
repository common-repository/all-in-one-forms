<?php 

namespace rednaoeasycalculationforms\DTO;

class ConfirmationItemBaseOptionsDTO extends ConditionBaseOptionsDTO{
	public $ConfirmationType;
	/** @var Boolean */
	public $EnableCondition;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->EnableCondition=false;
	}
}

