<?php


namespace rednaoeasycalculationforms\Parser\Elements;


use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParserElementBase;

class ParseDeclaration extends ParserElementBase
{
    public $VariableName;
    /** @var ParserElementBase */
    public $Assignment;
    public function __construct($parent, $data)
    {
        parent::__construct($parent, $data);
        $this->VariableName=$data->Name;
        $this->Assignment=ParseFactory::GetParseElement($this,$data->Assignment);
    }


    public function Parse()
    {
        $value=$this->Assignment->Parse();
        $this->GetMain()->SetVariable($this->VariableName,$value);
        return $value;
    }
}