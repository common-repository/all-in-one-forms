<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class LogOptionsDTO extends StoreBase{
	/** @var Boolean */
	public $Enable;
	public $LogType;


	public function LoadDefaultValues(){
		$this->Enable=false;
		$this->LogType='Everything';
	}
}

