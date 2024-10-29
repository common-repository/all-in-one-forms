<?php 

namespace rednaoeasycalculationforms\DTO;

class MultipleOptionsBaseOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var ItemOptionsDTO[] */
	public $Options;
	/** @var AdditionalColumnOptionsDTO[] */
	public $AdditionalOptionColumn;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Options=[];
		$this->Options=[];
		$this->AdditionalOptionColumn=[];
		$this->AddType("Options","ItemOptionsDTO");
		$this->AddType("AdditionalOptionColumn","AdditionalColumnOptionsDTO");
	}
}

