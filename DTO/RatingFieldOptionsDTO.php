<?php 

namespace rednaoeasycalculationforms\DTO;

class RatingFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var Numeric */
	public $DefaultValue;
	/** @var Numeric */
	public $NumberOfItems;
	/** @var Boolean */
	public $AllowHalf;
	/** @var Boolean */
	public $ReadOnly;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='rating';
		$this->Label='Rating';
		$this->ReadOnly=false;
		$this->DefaultValue=0;
		$this->NumberOfItems=5;
		$this->AllowHalf=false;
	}
}

