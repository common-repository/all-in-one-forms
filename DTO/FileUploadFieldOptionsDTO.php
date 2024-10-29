<?php 

namespace rednaoeasycalculationforms\DTO;

class FileUploadFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $Value;
	/** @var Boolean */
	public $AllowMultipleFiles;
	/** @var string */
	public $AllowedExtensions;
	/** @var string */
	public $ButtonLabel;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Value='';
		$this->Type=FieldTypeEnumDTO::$FileUpload;
		$this->AllowMultipleFiles=false;
		$this->AllowedExtensions='';
		$this->ButtonLabel='Add File';
		$this->Label='File';
	}
}

