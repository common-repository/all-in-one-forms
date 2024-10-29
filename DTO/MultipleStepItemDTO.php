<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class MultipleStepItemDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Title;
	/** @var Numeric[] */
	public $FieldIds;
	/** @var IconOptionsDTO */
	public $Icon;
	/** @var IconOptionsDTO */
	public $Image;
	/** @var ConditionBaseOptionsDTO[] */
	public $Conditions;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Title='';
		$this->FieldIds=[];
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Image=(new IconOptionsDTO())->Merge();
		$this->Conditions=[];
		$this->AddType("FieldIds","Numeric");
		$this->AddType("Conditions","ConditionBaseOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Conditions":
				return \rednaoeasycalculationforms\DTO\core\Factories\ConditionFactory::GetConditions($value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

