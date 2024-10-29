<?php 

namespace rednaoeasycalculationforms\DTO;

class ImagePickerItemOptionsDTO extends ItemOptionsDTO{
	/** @var IconOptionsDTO */
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type='ImagePicker';
		$this->Icon=(new IconOptionsDTO())->Merge();
	}
}

