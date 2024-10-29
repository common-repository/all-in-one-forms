<?php 

namespace rednaoeasycalculationforms\DTO;

class GroupFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Label;
	/** @var FBRowOptionsDTO[] */
	public $Rows;
	/** @var Boolean */
	public $ShowSubTotal;
	/** @var string */
	public $SubTotalLabel;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='List';
		$this->PriceType='basic';
		$this->Type=FieldTypeEnumDTO::$GroupPanel;
		$this->ShowSubTotal=false;
		$this->SubTotalLabel='Subtotal';
		$this->Rows=[];
		$this->IsFieldContainer=true;
		$this->AddType("Rows","FBRowOptionsDTO");
	}
}

