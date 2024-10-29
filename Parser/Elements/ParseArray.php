<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseArray extends ParseBase
{
    /** @var ParseBase[] */
    public $Items;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Items=[];
        foreach($options->d as $item)
        {
            $this->Items[]=ParseFactory::GetParseElement($item,$this);
        }
    }


    public function Parse($type = null)
    {
        $items=[];
        foreach($this->Items as $item)
        {
            $items[]=$item->Parse();
        }
        return $items;
    }
}