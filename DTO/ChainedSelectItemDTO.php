<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ChainedSelectItemDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Label;
	public $Index;


	public function LoadDefaultValues(){
		$this->Id=0;
	}
}

