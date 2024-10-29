<?php 

namespace rednaoeasycalculationforms\DTO;

class TextualImageLabelItemOptionsDTO extends ItemOptionsDTO{
	/** @var string */
	public $DefaultText;
	/** @var Numeric */
	public $Top;
	/** @var Numeric */
	public $Left;
	/** @var Numeric */
	public $Width;
	/** @var string */
	public $Placeholder;
	/** @var string */
	public $Label;
	/** @var Numeric */
	public $Id;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Id=0;
		$this->DefaultText='Text';
		$this->Label='';
		$this->Top=0;
		$this->Left=0;
		$this->Width=0;
		$this->Placeholder='';
	}
}

