<?php 

namespace rednaoeasycalculationforms\DTO;

class ShowHideConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var Boolean */
	public $ShowWhenTrue;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='ShowHide';
		$this->ShowWhenTrue=true;
	}
}

