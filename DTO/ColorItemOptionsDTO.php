<?php 

namespace rednaoeasycalculationforms\DTO;

class ColorItemOptionsDTO extends ItemOptionsDTO{
	/** @var string */
	public $Label;
	/** @var string */
	public $Color;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='';
		$this->Color='';
	}
}

