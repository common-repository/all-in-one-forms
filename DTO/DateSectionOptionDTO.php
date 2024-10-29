<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class DateSectionOptionDTO extends StoreBase{
	/** @var string */
	public $DefaultDate;
	/** @var string */
	public $Placeholder;
	/** @var string */
	public $Label;
	/** @var IconOptionsDTO */
	public $Icon;


	public function LoadDefaultValues(){
		$this->DefaultDate='';
		$this->Placeholder='';
		$this->Label='';
		$this->Icon=(new IconOptionsDTO())->Merge();
	}
}

