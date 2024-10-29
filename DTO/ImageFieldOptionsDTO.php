<?php 

namespace rednaoeasycalculationforms\DTO;

class ImageFieldOptionsDTO extends FieldBaseOptionsDTO{
	/** @var string */
	public $Style;
	/** @var string */
	public $Label;
	public $Alignment;
	public $Src;
	/** @var string */
	public $Width;
	/** @var string */
	public $Height;
	/** @var string */
	public $BannerStyle;
	/** @var Boolean */
	public $AllowZoom;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='';
		$this->Type=FieldTypeEnumDTO::$Image;
		$this->Src=null;
		$this->Alignment='';
		$this->Width='';
		$this->Height='';
		$this->BannerStyle='cover';
		$this->AllowZoom=false;
		$this->AddType("Src","Object");
	}
}

