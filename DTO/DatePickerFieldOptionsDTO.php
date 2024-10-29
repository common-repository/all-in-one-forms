<?php 

namespace rednaoeasycalculationforms\DTO;

class DatePickerFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultDate;
	/** @var string */
	public $Placeholder;
	/** @var Boolean */
	public $EnableTime;
	/** @var string */
	public $MinDate;
	/** @var string */
	public $MaxDate;
	/** @var Boolean */
	public $ShowQuantitySelector;
	/** @var string */
	public $Format;
	/** @var Numeric */
	public $WeekStartOn;
	public $Icon;
	/** @var Boolean */
	public $ReadOnly;
	/** @var Numeric */
	public $FirstDayOfWeek;
	/** @var String[] */
	public $DisableWeek;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->DisableWeek=[];
		$this->Type=FieldTypeEnumDTO::$Datepicker;
		$this->FirstDayOfWeek=0;
		$this->ReadOnly=false;
		$this->EnableTime=false;
		$this->Label='Date';
		$this->DefaultDate='';
		$this->Placeholder='';
		$this->Format='d/m/Y';
		$this->WeekStartOn=0;
		$this->MinDate='';
		$this->MaxDate='';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->AddType("Icon","Object");
		$this->AddType("DisableWeek","String");
	}
}

