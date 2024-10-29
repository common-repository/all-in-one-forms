<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class UniqueFieldGroupDTO extends StoreBase{
	/** @var Numeric[] */
	public $FieldIds;
	/** @var string */
	public $NumberOfTimes;
	public $ErrorMessage;


	public function LoadDefaultValues(){
		$this->FieldIds=[];
		$this->NumberOfTimes='';
		$this->ErrorMessage=null;
		$this->AddType("FieldIds","Numeric");
		$this->AddType("ErrorMessage","Object");
	}
}

