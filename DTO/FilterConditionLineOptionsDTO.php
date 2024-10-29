<?php 

namespace rednaoeasycalculationforms\DTO;

class FilterConditionLineOptionsDTO extends ConditionLineOptionsDTO{
	/** @var string */
	public $FieldPath;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->FieldPath='';
	}
}

