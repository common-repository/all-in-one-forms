<?php


namespace rednaoeasycalculationforms\Parser\Elements\Scalars;


use rednaoeasycalculationforms\Parser\Core\ParserElementBase;

class ParseVariable extends ParserElementBase
{
    public $VariableName;
    public function __construct($parent, $data)
    {
        parent::__construct($parent, $data);
        $this->VariableName=$data->d;

    }


    public function Parse()
    {
        return $this->GetMain()->GetVariable($this->VariableName);
    }
}