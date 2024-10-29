<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseReturn extends ParseBase
{
    /** @var ParseBase */
    public $Statement;
    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Statement = ParseFactory::GetParseElement($options->d, $this);
    }


    public function Parse($type = null)
    {
        ParseUtilities::NotifyReturnToParent($this);
        return $this->Statement->Parse();
    }
}