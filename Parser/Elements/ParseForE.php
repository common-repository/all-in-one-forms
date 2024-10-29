<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseForE extends ParseBase
{
    /** @var ParseBase */
    private $Expression;
    private $Value='';
    private $Key='';
    /** @var ParseBase */
    private $Statement;
    private $ShouldBreak;
    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Expression=ParseFactory::GetParseElement($options->E,$this);
        $this->Value=$options->V;
        $this->Key=$options->K;
        $this->Statement=ParseFactory::GetParseElement($options->D,$this);
    }

    public function Parse($type = null)
    {
        $result=$this->Expression->Parse();
        $keys=[];
        if(is_array($result))
            $keys=array_keys($result);
        else
            $keys=array_keys((array)$result);

        foreach($keys as $key)
        {
            $value=$result[$key];
            $this->GetRetriever()->SetVar($this->Value,$value);
            if($this->Key!='')
                $this->GetRetriever()->SetVar($this->Key,$key);

            $lastResult=$this->Statement->Parse();
            if($this->ShouldBreak)
                return $lastResult;
        }
    }

    public function ReturnWasExecuted()
    {
        $this->ShouldBreak=true;
        ParseUtilities::NotifyReturnToParent($this);
    }

    public function BreakWasExecuted()
    {
        $this->ShouldBreak=true;
    }
}