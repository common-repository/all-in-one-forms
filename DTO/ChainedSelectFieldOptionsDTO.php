<?php 

namespace rednaoeasycalculationforms\DTO;

class ChainedSelectFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var ChainedSelectItemDTO[] */
	public $Items;
	/** @var Boolean */
	public $ReadOnly;
	/** @var String[] */
	public $Columns;
	public $Alignment;
	/** @var string */
	public $EmptyPlaceholder;
	public $Source;
	/** @var string */
	public $SheetFileId;
	/** @var string */
	public $SheetTabId;
	/** @var Boolean */
	public $UseLastColumnAsPrice;
	/** @var Boolean */
	public $SyncAutomatically;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Items=[];
		$this->ReadOnly=false;
		$this->Type='chained';
		$this->Columns=[];
		$this->Alignment='h';
		$this->Label='Chained Select';
		$this->EmptyPlaceholder='Select an item';
		$this->Source='File';
		$this->SheetFileId='';
		$this->SheetTabId='';
		$this->UseLastColumnAsPrice=false;
		$this->SyncAutomatically=false;
		$this->AddType("Items","ChainedSelectItemDTO");
		$this->AddType("Columns","String");
	}
}

