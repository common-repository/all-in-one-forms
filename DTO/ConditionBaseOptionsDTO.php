<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class ConditionBaseOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Type;
	/** @var ConditionGroupOptionsDTO[] */
	public $ConditionGroups;
	/** @var ElementUsedOptionsDTO[] */
	public $ElementsUsed;
	public $Formula;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Type='ShowHide';
		$this->ElementsUsed=[];
		$this->ConditionGroups=[];
		$this->Formula=null;
		$this->AddType("ConditionGroups","ConditionGroupOptionsDTO");
		$this->AddType("ElementsUsed","ElementUsedOptionsDTO");
		$this->AddType("Formula","Object");
	}
}

