<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseCondE extends ParseBase
{
    /** @var ParseBase */
    public $Cond;
    /** @var ParseBase */
    public $Tr;
    /** @var ParseBase */
    public $Fa;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Cond=ParseFactory::GetParseElement($options->C,$this);
        $this->Tr=ParseFactory::GetParseElement($options->Tr,$this);
        $this->Fa=ParseFactory::GetParseElement($options->Fa,$this);
    }


    public function Parse($type = null)
    {
        if($this->Cond->Parse())
            return $this->Tr->Parse();
        else
            return $this->Fa->Parse();
    }
}