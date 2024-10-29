<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class FBColumnOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var Numeric */
	public $WidthPercentage;
	/** @var FieldBaseOptionsDTO */
	public $Field;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->WidthPercentage=100;
		$this->Field=null;
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Field":
				return \rednaoeasycalculationforms\DTO\core\Factories\FieldFactory::GetFieldOptions($value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

