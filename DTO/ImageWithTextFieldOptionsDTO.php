<?php 

namespace rednaoeasycalculationforms\DTO;

class ImageWithTextFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	public $Image;
	/** @var Numeric */
	public $ImageHeight;
	/** @var Numeric */
	public $ImageWidth;
	/** @var TextualImageLabelItemOptionsDTO[] */
	public $Texts;
	/** @var Boolean */
	public $ShowQuantitySelector;
	public $FreeCharOrWords;
	/** @var Boolean */
	public $IgnoreSpaces;
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$TextualImage;
		$this->Label='Textual Image';
		$this->Image=null;
		$this->ImageWidth=400;
		$this->ImageHeight=400;
		$this->Texts=[];
		$this->IgnoreSpaces=false;
		$this->ShowQuantitySelector=false;
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->FreeCharOrWords=0;
		$this->AddType("Image","Object");
		$this->AddType("Texts","TextualImageLabelItemOptionsDTO");
		$this->AddType("Icon","Object");
	}
}

