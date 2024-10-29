<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class EntryNumberingOptionsDTO extends StoreBase{
	public $Prefix;
	public $Suffix;
	/** @var Numeric */
	public $NumberOfDigits;


	public function LoadDefaultValues(){
		$this->Prefix=null;
		$this->Suffix=null;
		$this->NumberOfDigits=0;
		$this->AddType("Prefix","Object");
		$this->AddType("Suffix","Object");
	}
}

