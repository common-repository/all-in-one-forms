<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class AdditionalColumnOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Label;
	public $Options;
	/** @var string */
	public $Type;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Label='';
		$this->Options=null;
		$this->Type='';
		$this->AddType("Options","Object");
	}
}

