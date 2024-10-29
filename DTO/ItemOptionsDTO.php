<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ItemOptionsDTO extends StoreBase{
	/** @var string */
	public $Label;
	/** @var string */
	public $RegularPrice;
	/** @var Numeric */
	public $Id;
	/** @var Boolean */
	public $Selected;
	/** @var string */
	public $Type;
	/** @var AdditionalOptionValueDTO[] */
	public $AdditionalOptionValue;


	public function LoadDefaultValues(){
		$this->Type='';
		$this->Selected=false;
		$this->Id=0;
		$this->Label='';
		$this->RegularPrice='';
		$this->AdditionalOptionValue=[];
		$this->AddType("AdditionalOptionValue","AdditionalOptionValueDTO");
	}
}

