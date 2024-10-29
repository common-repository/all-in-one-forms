<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class MultipleStepOptionsDTO extends StoreBase{
	/** @var string */
	public $NextButtonText;
	/** @var string */
	public $PreviousButtonText;
	/** @var string */
	public $SubmitButtonText;
	/** @var MultipleStepItemDTO[] */
	public $Steps;
	/** @var string */
	public $Style;
	/** @var Boolean */
	public $IncludeStepImage;
	/** @var string */
	public $ImagePosition;
	/** @var string */
	public $ImageWidth;
	/** @var string */
	public $ImageHeight;


	public function LoadDefaultValues(){
		$this->Style='Tabs';
		$this->NextButtonText='Next';
		$this->PreviousButtonText='Previous';
		$this->SubmitButtonText='Submit';
		$this->Steps=[];
		$this->IncludeStepImage=false;
		$this->ImagePosition='top';
		$this->ImageWidth='100%';
		$this->ImageHeight='300px';
		$this->AddType("Steps","MultipleStepItemDTO");
	}
}

