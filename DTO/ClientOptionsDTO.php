<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ClientOptionsDTO extends StoreBase{
	/** @var string */
	public $Styles;
	/** @var string */
	public $Javascript;
	/** @var string */
	public $MapsApiKey;
	/** @var Boolean */
	public $EnableDebug;
	public $Extensions;
	public $Icons;
	/** @var MultipleStepOptionsDTO */
	public $MultipleSteps;
	/** @var String[] */
	public $ExtensionsUsed;
	/** @var string */
	public $RequiredMessage;
	/** @var string */
	public $Theme;


	public function LoadDefaultValues(){
		$this->Styles='';
		$this->Javascript='';
		$this->EnableDebug=false;
		$this->Icons=[];
		$this->MapsApiKey='';
		$this->MultipleSteps=null;
		$this->Extensions=[];
		$this->ExtensionsUsed=[];
		$this->RequiredMessage='Required';
		$this->Theme='';
		$this->AddType("Extensions","Object");
		$this->AddType("Icons","Object");
		$this->AddType("MultipleSteps","MultipleStepOptionsDTO");
		$this->AddType("ExtensionsUsed","String");
	}
}

