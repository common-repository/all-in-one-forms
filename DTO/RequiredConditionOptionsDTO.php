<?php 

namespace rednaoeasycalculationforms\DTO;

class RequiredConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var Boolean */
	public $IsRequiredWhenTrue;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Required';
		$this->IsRequiredWhenTrue=true;
	}
}

