<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class CurrencyOptionsDTO extends StoreBase{
	/** @var string */
	public $Format;
	/** @var Numeric */
	public $Decimals;
	/** @var string */
	public $ThousandSeparator;
	/** @var string */
	public $DecimalSeparator;
	/** @var string */
	public $Symbol;


	public function LoadDefaultValues(){
		$this->Format='%1$s%2$s';
		$this->Decimals=2;
		$this->ThousandSeparator=',';
		$this->DecimalSeparator='.';
		$this->Symbol='$';
	}
}

