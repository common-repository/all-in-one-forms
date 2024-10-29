<?php


namespace rednaoeasycalculationforms\core\Utils;


class FormSettingsIterator
{
    public $FormOptions;
    public function __construct($formOptions)
    {
        $this->FormOptions=$formOptions;
    }

    public function Iterate($fn)
    {
        $this->InternalIterate($this->FormOptions->Rows,$fn);
    }

    private function InternalIterate($rowList,$fn){

        foreach($rowList as $row)
            foreach($row->Columns as $column)
            {
                $field=$column->Field;
                $fn($field);

                if($field->Type=='repeater')
                    $this->InternalIterate($field->RowTemplates,$fn);

                if($field->Type=='grouppanel')
                    $this->InternalIterate($field->Rows,$fn);

            }
    }


}