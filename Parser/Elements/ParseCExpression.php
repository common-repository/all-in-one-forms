<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;

class ParseCExpression extends ParseBase{
    public $FunctionName;
    /** @var ParseBase */
    private $Args;
    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->FunctionName=$options->Fn;
        $this->Args=[];
        foreach($options->Ar as $arg)
            $this->Args[]=ParseFactory::GetParseElement($arg,$this);
    }


    public function Parse($type = null)
    {
        $args=[];
        foreach($this->Args as $arg)
            $args[]=$arg->Parse();
        return $this->GetRetriever()->CallFunction($this->FunctionName,$args);
    }
}