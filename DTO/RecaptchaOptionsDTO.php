<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class RecaptchaOptionsDTO extends StoreBase{
	/** @var string */
	public $Type;
	/** @var string */
	public $SiteKey;
	/** @var string */
	public $SecretKey;
	/** @var string */
	public $MinimumScore;


	public function LoadDefaultValues(){
		$this->Type='';
		$this->SiteKey='';
		$this->SecretKey='';
		$this->MinimumScore='';
	}
}

