<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class RestrictionBaseOptionsDTO extends StoreBase{
	public $Type;
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Type=RestrictionTypeEnumDTO::$None;
	}
}

