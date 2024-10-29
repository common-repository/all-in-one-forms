<?php 

namespace rednaoeasycalculationforms\DTO;

class RepeaterFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Label;
	/** @var RepeaterItemOptionsDTO */
	public $RepeaterItemTemplate;
	public $RepeaterType;
	/** @var Numeric */
	public $RepeatNumberOfTimes;
	/** @var string */
	public $SubTotalLabel;
	/** @var Boolean */
	public $ShowSubTotal;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='List';
		$this->Type=FieldTypeEnumDTO::$Repeater;
		$this->RepeaterItemTemplate=(new RepeaterItemOptionsDTO())->Merge();
		$this->IsFieldContainer=true;
		$this->PriceType='total_inside_repeater';
		$this->RepeaterType=RepeaterTypeEnumDTO::$AddRemoveButton;
		$this->RepeatNumberOfTimes=1;
		$this->ShowSubTotal=false;
		$this->SubTotalLabel='Subtotal';
		$this->AddType("RepeaterItemTemplate","RepeaterItemOptionsDTO");
	}
}

