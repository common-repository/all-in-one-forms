<?php 

namespace rednaoeasycalculationforms\DTO;

class WebsiteFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultText;
	/** @var string */
	public $Placeholder;
	/** @var Boolean */
	public $ReadOnly;
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ReadOnly=false;
		$this->Type='website';
		$this->Label='Website';
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Placeholder='';
		$this->DefaultText='';
		$this->AddType("Icon","Object");
	}
}

