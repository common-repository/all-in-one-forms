<?php 

namespace rednaoeasycalculationforms\DTO;

class SkipRepeaterItemConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var Boolean */
	public $SkipWhenTrue;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='SkipRepeaterItem';
		$this->SkipWhenTrue=true;
	}
}

