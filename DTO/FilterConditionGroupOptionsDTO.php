<?php 

namespace rednaoeasycalculationforms\DTO;

class FilterConditionGroupOptionsDTO extends ConditionGroupOptionsDTO{
	/** @var FilterConditionLineOptionsDTO[] */
	public $ConditionLines;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->AddType("ConditionLines","FilterConditionLineOptionsDTO");
	}
}

