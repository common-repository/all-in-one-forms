<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ConditionLineOptionsDTO extends StoreBase{
	/** @var string */
	public $FieldId;
	public $Comparison;
	public $Value;
	public $Type;
	public $SubType;
	/** @var string */
	public $ValueSubType;
	/** @var string */
	public $PathId;
	public $Formula;
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->FieldId='';
		$this->Comparison=ComparisonTypeEnumDTO::$None;
		$this->Value='';
		$this->Type=ConditionLineTypeEnumDTO::$None;
		$this->SubType=SubTypeEnumDTO::$Standard;
		$this->ValueSubType='';
		$this->PathId='';
		$this->Formula=null;
		$this->AddType("Value","Object");
		$this->AddType("Formula","Object");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Value":
				return \rednaoeasycalculationforms\DTO\core\Factories\ConditionLineFactory::GetValue($this,$value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

