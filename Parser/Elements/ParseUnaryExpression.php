<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseUnaryExpression extends ParseBase
{
    private $Subtype;
    /** @var ParseBase */
    private $Expression;


    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Subtype=$options->St;
        $this->Expression=ParseFactory::GetParseElement($options->d,$this);
    }

    public function Parse($type = null)
    {
        if($this->Subtype=="-")
            return $this->Expression->Parse()*-1;
        return !$this->Expression->Parse();
    }
}