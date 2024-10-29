<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class BuilderOptionsDTO extends StoreBase{
	/** @var FormBuilderOptionsDTO */
	public $FormBuilder;
	/** @var ServerOptionsDTO */
	public $ServerOptions;
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Name;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->FormBuilder=(new FormBuilderOptionsDTO())->Merge();
		$this->ServerOptions=(new ServerOptionsDTO())->Merge();
		$this->Name='';
	}
}

