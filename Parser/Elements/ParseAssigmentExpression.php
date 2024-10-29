<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseAssigmentExpression extends ParseBase
{
    /** @var ParseBase */
    public $Assignee;
    /** @var ParseBase */
    private $Value;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Assignee=ParseFactory::GetParseElement($options->As,$this);
        $this->Value=ParseFactory::GetParseElement($options->D,$this);
    }

    public function Parse($type = null)
    {
        $value=$this->Value->Parse();
        if($this->Assignee->GetType()=='Var')
            $this->Assignee->Assign($value);
        else
            $this->Assignee->Assign($value);
    }
}