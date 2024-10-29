<?php 

namespace rednaoeasycalculationforms\DTO;

class GoogleMapsFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var Boolean */
	public $ShowMap;
	/** @var Boolean */
	public $ShowQuantitySelector;
	/** @var String[] */
	public $RestrictedCountries;
	/** @var string */
	public $Placeholder;
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
	public $DefaultCountry;
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
	/** @var Boolean */
	public $ConnectPoints;
	/** @var Boolean */
	public $ShowMarkerAddress;
	/** @var Numeric */
	public $NumberOfMarkers;
	/** @var string */
	public $DistanceType;
	/** @var Numeric */
	public $PricePerDistance;
	/** @var String[] */
	public $MarkerLabels;
	/** @var string */
	public $PointOfOrigin;
	/** @var string */
	public $PointOfOriginLat;
	/** @var string */
	public $PointOfOriginLng;
	/** @var string */
	public $AddressType;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$GoogleMaps;
		$this->Label='Google Maps';
		$this->DefaultCountry='';
		$this->RestrictedCountries=[];
		$this->NumberOfMarkers=1;
		$this->ShowAddress2=true;
		$this->ShowCity=true;
		$this->ShowState=true;
		$this->ShowZip=true;
		$this->ShowCountry=true;
		$this->ShowQuantitySelector=false;
		$this->QuantityPosition='bottom';
		$this->QuantityMaximumValue=0;
		$this->QuantityMinimumValue=0;
		$this->QuantityDefaultValue='';
		$this->QuantityPlaceholder='';
		$this->QuantityLabel='Quantity';
		$this->ConnectPoints=false;
		$this->Address1Label='Address 1';
		$this->Address2Label='Address 2';
		$this->CityLabel='City';
		$this->StateLabel='State';
		$this->ZipLabel='Zip';
		$this->CountryLabel='Country';
		$this->AddressType='multiple';
		$this->ShowMap=true;
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->ShowMarkerAddress=false;
		$this->DistanceType='Kilometer';
		$this->PricePerDistance=1;
		$this->MarkerLabels=[];
		$this->PointOfOrigin='';
		$this->PointOfOriginLat='';
		$this->PointOfOriginLng='';
		$this->Placeholder='Enter a location';
		$this->AddType("RestrictedCountries","String");
		$this->AddType("MarkerLabels","String");
	}
}

