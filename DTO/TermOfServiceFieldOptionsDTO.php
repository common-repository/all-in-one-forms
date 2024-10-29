<?php 

namespace rednaoeasycalculationforms\DTO;

class TermOfServiceFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var string */
	public $Text;
	public $LinkType;
	/** @var string */
	public $PopUpTitle;
	public $PopUpContent;
	/** @var string */
	public $LinkUrl;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Type=FieldTypeEnumDTO::$TermOfService;
		$this->Label='Term of Service';
		$this->LinkType='popup';
		$this->Text='I agree to $$Term of Service$$';
		$this->PopUpTitle='Term of service';
		$this->PopUpContent=null;
		$this->LinkUrl='http://example.com';
		$this->PriceType=PriceTypeEnumDTO::$none;
		$this->AddType("PopUpContent","Object");
	}
}

