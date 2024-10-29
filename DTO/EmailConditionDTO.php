<?php 

namespace rednaoeasycalculationforms\DTO;

class EmailConditionDTO extends ConditionBaseOptionsDTO{
	/** @var Boolean */
	public $SendWhenTrue;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->SendWhenTrue=true;
	}
}

