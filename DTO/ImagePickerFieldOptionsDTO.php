<?php 

namespace rednaoeasycalculationforms\DTO;

class ImagePickerFieldOptionsDTO extends MultipleOptionsBaseOptionsDTO{
	/** @var ImagePickerItemOptionsDTO[] */
	public $Options;
	/** @var string */
	public $Placeholder;
	/** @var string */
	public $ImageWidth;
	/** @var string */
	public $ImageHeight;
	/** @var Boolean */
	public $AllowMultipleSelection;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ImageWidth='100%';
		$this->ImageHeight='';
		$this->Options=[];
		$this->Type=FieldTypeEnumDTO::$ImagePicker;
		$this->Label='Image Picker';
		$this->Placeholder='';
		$this->PriceType=PriceTypeEnumDTO::$options;
		$this->AllowMultipleSelection=false;
		$this->Options=[];
		$this->AddType("Options","ImagePickerItemOptionsDTO");
	}
}

