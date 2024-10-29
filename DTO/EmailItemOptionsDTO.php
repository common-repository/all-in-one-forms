<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class EmailItemOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Name;
	/** @var EmailAddressDTO[] */
	public $To;
	/** @var EmailAddressDTO[] */
	public $ReplyTo;
	/** @var EmailAddressDTO[] */
	public $CC;
	/** @var EmailAddressDTO[] */
	public $BCC;
	/** @var string */
	public $FromName;
	/** @var string */
	public $FromEmailAddress;
	public $Subject;
	public $Content;
	/** @var Boolean */
	public $EnableConditions;
	/** @var EmailConditionDTO */
	public $Condition;
	public $Extensions;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Name='';
		$this->To=[];
		$this->ReplyTo=[];
		$this->CC=[];
		$this->BCC=[];
		$this->FromName='';
		$this->FromEmailAddress='';
		$this->Content=null;
		$this->Subject=null;
		$this->EnableConditions=false;
		$this->Extensions=[];
		$this->Condition=(new EmailConditionDTO())->Merge();
		$this->AddType("To","EmailAddressDTO");
		$this->AddType("ReplyTo","EmailAddressDTO");
		$this->AddType("CC","EmailAddressDTO");
		$this->AddType("BCC","EmailAddressDTO");
		$this->AddType("Subject","Object");
		$this->AddType("Content","Object");
		$this->AddType("Extensions","Object");
	}
}

