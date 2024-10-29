<?php 

namespace rednaoeasycalculationforms\DTO;

class PageConfirmationItemOptionsDTO extends ConfirmationItemBaseOptionsDTO{
	/** @var Numeric */
	public $PageId;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ConfirmationType=ConfirmationTypeEnumDTO::$Page;
		$this->PageId=0;
	}
}

