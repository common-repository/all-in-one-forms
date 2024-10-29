<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ConfirmationBaseOptionsDTO extends StoreBase{
	/** @var ConfirmationItemBaseOptionsDTO[] */
	public $ConfirmationItem;


	public function LoadDefaultValues(){
		$this->ConfirmationItem=[];
		$this->AddType("ConfirmationItem","ConfirmationItemBaseOptionsDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "ConfirmationItem":
				return \rednaoeasycalculationforms\DTO\core\Factories\ConfirmationItemFactory::GetConfirmationItem($value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

