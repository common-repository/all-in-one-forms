<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseBlock extends ParseBase
{
    /** @var ParseBase[] */
    private $Sentences;
    public $ShouldReturn=false;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Sentences = [];
        foreach($options->d as $sentence)
        {
            $this->Sentences[] = ParseFactory::GetParseElement($sentence, $this);
        }

    }


    public function Parse($type = null)
    {
        $result=null;
        foreach($this->Sentences as $sentence)
        {
            $result=$sentence->Parse();
            if($this->ShouldReturn)
                return $result;
        }

        return $result;
    }

    public function ReturnWasExecuted()
    {
        $this->ShouldReturn=true;
        ParseUtilities::NotifyReturnToParent($this);
    }
}