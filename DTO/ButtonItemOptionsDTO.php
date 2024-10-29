<?php 

namespace rednaoeasycalculationforms\DTO;

class ButtonItemOptionsDTO extends ItemOptionsDTO{
	/** @var IconOptionsDTO */
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='Button';
		$this->Icon=(new IconOptionsDTO())->Merge();
	}
}

