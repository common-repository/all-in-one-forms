<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseIfStatement extends ParseBase
{
    /** @var ParseBase */
    private $Condition;
    /** @var ParseBase */
    private $TrueAction;
    /** @var ParseBase */
    private $FalseAction;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Condition = ParseFactory::GetParseElement($options->Con, $this);
        $this->TrueAction = ParseFactory::GetParseElement($options->Tr, $this);
        if(isset($this->Options->Fa)&&$this->Options->Fa!=null)
            $this->FalseAction = ParseFactory::GetParseElement($options->Fa, $this);
    }

    public function Parse($type = null)
    {
        if($this->Condition->Parse())
            return $this->TrueAction->Parse();
        else {
            if ($this->FalseAction != null)
                return $this->FalseAction->Parse();
        }
        return false;
    }
}