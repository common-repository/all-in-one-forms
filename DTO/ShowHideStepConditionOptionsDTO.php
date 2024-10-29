<?php 

namespace rednaoeasycalculationforms\DTO;

class ShowHideStepConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var Boolean */
	public $ShowWhenTrue;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='ShowHideStep';
		$this->ShowWhenTrue=true;
	}
}

