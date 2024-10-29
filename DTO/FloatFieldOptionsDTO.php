<?php 

namespace rednaoeasycalculationforms\DTO;

class FloatFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Label;
	/** @var FBRowOptionsDTO[] */
	public $Rows;
	/** @var Boolean */
	public $ShowSubTotal;
	/** @var string */
	public $SubTotalLabel;
	/** @var string */
	public $Position;
	/** @var string */
	public $Width;
	/** @var Numeric */
	public $TopOffset;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='List';
		$this->Type=FieldTypeEnumDTO::$FloatPanel;
		$this->PriceType='float';
		$this->ShowSubTotal=false;
		$this->SubTotalLabel='Subtotal';
		$this->Rows=[];
		$this->IsFieldContainer=true;
		$this->Position='right';
		$this->Width='300';
		$this->TopOffset=2;
		$this->AddType("Rows","FBRowOptionsDTO");
	}
}

