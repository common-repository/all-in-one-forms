<?php 

namespace rednaoeasycalculationforms\DTO;

class FieldSummaryTemplateOptionsDTO extends TemplateBaseOptionsDTO{
	/** @var string */
	public $Format;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Id='field_summary';
		$this->Format='one_row_per_field';
	}
}

