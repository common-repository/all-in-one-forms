<?php 

namespace rednaoeasycalculationforms\DTO;

class ListFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Label;
	/** @var Boolean */
	public $EnableMultipleColumns;
	/** @var ListColumnOptionsDTO[] */
	public $Columns;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='List';
		$this->EnableMultipleColumns=false;
		$this->Type=FieldTypeEnumDTO::$List;
		$this->Columns=[];
		$this->AddType("Columns","ListColumnOptionsDTO");
	}
}

