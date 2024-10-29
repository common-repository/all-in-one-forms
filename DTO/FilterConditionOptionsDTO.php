<?php 

namespace rednaoeasycalculationforms\DTO;

class FilterConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var FilterConditionGroupOptionsDTO[] */
	public $ConditionGroups;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->AddType("ConditionGroups","FilterConditionGroupOptionsDTO");
	}
}

