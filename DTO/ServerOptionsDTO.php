<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ServerOptionsDTO extends StoreBase{
	/** @var EmailItemOptionsDTO[] */
	public $Emails;
	public $Extensions;
	/** @var string */
	public $DefaultStatus;
	/** @var EntryNumberingOptionsDTO */
	public $EntryNumbering;
	/** @var Boolean */
	public $ExtendedValidation;
	/** @var RestrictionBaseOptionsDTO[] */
	public $Restrictions;
	/** @var ConfirmationBaseOptionsDTO */
	public $ConfirmationOptions;
	/** @var ServerDependencyDTO[] */
	public $ServerDependencies;


	public function LoadDefaultValues(){
		$this->Emails=[];
		$this->Extensions=[];
		$this->ExtendedValidation=false;
		$this->DefaultStatus='completed';
		$this->ConfirmationOptions=(new ConfirmationBaseOptionsDTO())->Merge();
		$this->EntryNumbering=(new EntryNumberingOptionsDTO())->Merge();
		$this->Restrictions=[];
		$this->ServerDependencies=[];
		$this->AddType("Emails","EmailItemOptionsDTO");
		$this->AddType("Extensions","Object");
		$this->AddType("Restrictions","RestrictionBaseOptionsDTO");
		$this->AddType("ServerDependencies","ServerDependencyDTO");
	}
	public function GetValueFromLoader($property,$value){
		switch($property){
			case "Restrictions":
				return \rednaoeasycalculationforms\DTO\core\Factories\RestrictionFactory::GetRestrictions($value);
			default:
				return parent::GetValueFromLoader($property, $value);
		}
	}
}

