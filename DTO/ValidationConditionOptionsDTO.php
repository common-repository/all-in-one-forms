<?php 

namespace rednaoeasycalculationforms\DTO;

class ValidationConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var Boolean */
	public $IsInvalidWhenTrue;
	/** @var string */
	public $InvalidMessage;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Validation';
		$this->IsInvalidWhenTrue=true;
		$this->InvalidMessage='Invalid Field';
	}
}

