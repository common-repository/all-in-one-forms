<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class EmailMappingDTO extends StoreBase{
	/** @var string */
	public $Value;
	/** @var string */
	public $Email;


	public function LoadDefaultValues(){
		$this->Value='';
		$this->Email='';
	}
}

