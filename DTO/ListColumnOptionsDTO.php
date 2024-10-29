<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ListColumnOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Name;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Name='';
	}
}

