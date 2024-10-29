<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class EmailAddressDTO extends StoreBase{
	public $Type;
	/** @var string */
	public $Value;
	/** @var EmailMappingDTO[] */
	public $EmailMapping;


	public function LoadDefaultValues(){
		$this->Value='';
		$this->Type=EmailAddressEnumDTO::$Fixed;
		$this->EmailMapping=[];
		$this->AddType("EmailMapping","EmailMappingDTO");
	}
}

