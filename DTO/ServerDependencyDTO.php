<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ServerDependencyDTO extends StoreBase{
	/** @var string */
	public $Type;
	/** @var string */
	public $Id;
	public $Options;


	public function LoadDefaultValues(){
		$this->Type='';
		$this->Id='';
		$this->Options=null;
		$this->AddType("Options","Object");
	}
}

