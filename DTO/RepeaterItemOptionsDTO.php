<?php 

namespace rednaoeasycalculationforms\DTO;

class RepeaterItemOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Label;
	/** @var FBRowOptionsDTO[] */
	public $Rows;
	/** @var Boolean */
	public $ShowSubTotal;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='List';
		$this->Type=FieldTypeEnumDTO::$RepeaterItem;
		$this->Rows=[];
		$this->IsFieldContainer=true;
		$this->ShowSubTotal=false;
		$this->HidePrice=true;
		$this->PriceType='total_inside_repeater';
		$this->AddType("Rows","FBRowOptionsDTO");
	}
}

