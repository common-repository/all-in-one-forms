<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class AdditionalOptionValueDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Value;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Value='';
	}
}

