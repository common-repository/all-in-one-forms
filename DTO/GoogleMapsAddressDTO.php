<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class GoogleMapsAddressDTO extends StoreBase{
	/** @var string */
	public $StreetAddress1;
	/** @var string */
	public $StreetAddress2;
	/** @var string */
	public $City;
	/** @var string */
	public $State;
	/** @var string */
	public $Zip;
	/** @var string */
	public $CountryShort;
	/** @var string */
	public $CountryLong;
	/** @var string */
	public $FormattedAddress;


	public function LoadDefaultValues(){
		$this->StreetAddress1='';
		$this->StreetAddress2='';
		$this->City='';
		$this->State='';
		$this->Zip='';
		$this->CountryShort='';
		$this->CountryLong='';
	}
}

