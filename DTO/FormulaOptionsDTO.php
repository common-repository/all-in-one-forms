<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class FormulaOptionsDTO extends StoreBase{
	/** @var string */
	public $Code;
	/** @var string */
	public $Name;
	/** @var String[] */
	public $Dependencies;
	/** @var Numeric[] */
	public $Fields;
	public $PreferredReturnType;
	/** @var Boolean */
	public $SkipFieldListener;
	public $Compiled;


	public function LoadDefaultValues(){
		$this->Code='';
		$this->Name='';
		$this->Compiled=null;
		$this->PreferredReturnType=PreferredReturnTypeEnumDTO::$Any;
		$this->Dependencies=[];
		$this->Fields=[];
		$this->SkipFieldListener=false;
		$this->AddType("Dependencies","String");
		$this->AddType("Fields","Numeric");
		$this->AddType("Compiled","Object");
	}
}

