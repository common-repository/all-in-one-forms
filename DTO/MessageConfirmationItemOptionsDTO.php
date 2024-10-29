<?php 

namespace rednaoeasycalculationforms\DTO;

class MessageConfirmationItemOptionsDTO extends ConfirmationItemBaseOptionsDTO{
	/** @var string */
	public $Title;
	public $Content;
	/** @var string */
	public $ButtonText;
	/** @var Boolean */
	public $RedirectAfterConfirm;
	public $URL;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->ConfirmationType=ConfirmationTypeEnumDTO::$Message;
		$this->Title='';
		$this->Content=null;
		$this->ButtonText='';
		$this->RedirectAfterConfirm=false;
		$this->URL=null;
		$this->AddType("Content","Object");
		$this->AddType("URL","Object");
	}
}

