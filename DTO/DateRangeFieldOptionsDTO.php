<?php 

namespace rednaoeasycalculationforms\DTO;

class DateRangeFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var Boolean */
	public $ShowQuantitySelector;
	/** @var string */
	public $Format;
	public $Orientation;
	/** @var DateSectionOptionDTO */
	public $StartDate;
	/** @var DateSectionOptionDTO */
	public $EndDate;
	/** @var Boolean */
	public $EnableTime;
	/** @var Numeric */
	public $FirstDayOfWeek;
	/** @var String[] */
	public $SDisableWeek;
	/** @var String[] */
	public $EDisableWeek;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->SDisableWeek=[];
		$this->EDisableWeek=[];
		$this->FirstDayOfWeek=0;
		$this->EnableTime=false;
		$this->Type=FieldTypeEnumDTO::$DateRange;
		$this->Format='d/m/Y';
		$this->Orientation='horizontal';
		$this->Label='Dater Range';
		$this->StartDate=(new DateSectionOptionDTO())->Merge();
		$this->EndDate=(new DateSectionOptionDTO())->Merge();
		$this->AddType("SDisableWeek","String");
		$this->AddType("EDisableWeek","String");
	}
}

