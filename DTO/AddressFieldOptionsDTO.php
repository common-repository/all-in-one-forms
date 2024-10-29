<?php 

namespace rednaoeasycalculationforms\DTO;

class AddressFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $DefaultText;
	/** @var string */
	public $Address1Label;
	/** @var string */
	public $Address2Label;
	/** @var string */
	public $CityLabel;
	/** @var string */
	public $StateLabel;
	/** @var string */
	public $ZipLabel;
	/** @var string */
	public $CountryLabel;
	/** @var string */
	public $Address1Placeholder;
	/** @var string */
	public $Address2Placeholder;
	/** @var string */
	public $CityPlaceholder;
	/** @var string */
	public $StatePlaceholder;
	/** @var string */
	public $ZipPlaceholder;
	/** @var string */
	public $CountryPlaceholder;
	/** @var string */
	public $Address1DefaultValue;
	/** @var string */
	public $Address2DefaultValue;
	/** @var string */
	public $CityDefaultValue;
	/** @var string */
	public $StateDefaultValue;
	/** @var string */
	public $ZipDefaultValue;
	/** @var string */
	public $CountryDefaultValue;
	/** @var Boolean */
	public $ShowAddress2;
	/** @var Boolean */
	public $ShowCity;
	/** @var Boolean */
	public $ShowState;
	/** @var Boolean */
	public $ShowZip;
	/** @var Boolean */
	public $ShowCountry;
	/** @var string */
	public $SelectedCountry;
	public $Icon;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$Address;
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Label='Address';
		$this->Address1Label='Address 1';
		$this->Address2Label='Address 2';
		$this->CityLabel='City';
		$this->StateLabel='State';
		$this->ZipLabel='Zip';
		$this->CountryLabel='Country';
		$this->SelectedCountry='Afghanistan';
		$this->Address1Placeholder='';
		$this->Address2Placeholder='';
		$this->CityPlaceholder='';
		$this->StatePlaceholder='';
		$this->ZipPlaceholder='';
		$this->CountryPlaceholder='';
		$this->Address1DefaultValue='';
		$this->Address2DefaultValue='';
		$this->CityDefaultValue='';
		$this->StateDefaultValue='';
		$this->ZipDefaultValue='';
		$this->CountryDefaultValue='';
		$this->ShowAddress2=true;
		$this->ShowCity=true;
		$this->ShowState=true;
		$this->ShowZip=true;
		$this->ShowCountry=true;
		$this->AddType("Icon","Object");
	}
}

