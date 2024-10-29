<?php 

namespace rednaoeasycalculationforms\DTO;

class ChangeOptionsPriceConditionOptionsDTO extends ConditionBaseOptionsDTO{
	/** @var string */
	public $PriceName;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='ChangeOptionsPrice';
		$this->PriceName='Price';
	}
}

