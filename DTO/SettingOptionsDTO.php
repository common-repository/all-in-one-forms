<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class SettingOptionsDTO extends StoreBase{
	/** @var CurrencyOptionsDTO */
	public $Currency;
	/** @var LogOptionsDTO */
	public $LogOptions;
	/** @var Boolean */
	public $EnableDebugMode;
	/** @var string */
	public $GoogleMapsApiKey;
	/** @var RecaptchaOptionsDTO */
	public $Recaptcha;
	/** @var Numeric */
	public $MinimumScore;
	/** @var string */
	public $License;
	public $AddOnOptions;


	public function LoadDefaultValues(){
		$this->License='';
		$this->Currency=(new CurrencyOptionsDTO())->Merge();
		$this->LogOptions=(new LogOptionsDTO())->Merge();
		$this->Recaptcha=(new RecaptchaOptionsDTO())->Merge();
		$this->EnableDebugMode=false;
		$this->GoogleMapsApiKey='';
		$this->MinimumScore=0.4;
		$this->AddOnOptions=(Object)[];;
		$this->AddType("AddOnOptions","Object");
	}
}

