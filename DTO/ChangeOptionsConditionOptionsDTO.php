<?php 

namespace rednaoeasycalculationforms\DTO;

class ChangeOptionsConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var ItemOptionsDTO[] */
	public $Options;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='ChangeOptions';
		$this->Options=[];
		$this->AddType("Options","ItemOptionsDTO");
	}
}

