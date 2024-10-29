<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class FBRowOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var Numeric */
	public $StepId;
	/** @var FBColumnOptionsDTO[] */
	public $Columns;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->StepId=0;
		$this->Columns=[];
		$this->AddType("Columns","FBColumnOptionsDTO");
	}
}

