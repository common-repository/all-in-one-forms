<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class TemplateBaseOptionsDTO extends StoreBase{
	/** @var string */
	public $Id;


	public function LoadDefaultValues(){
		$this->Id='';
	}
}

