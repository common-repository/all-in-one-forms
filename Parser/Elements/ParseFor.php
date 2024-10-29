<?php

namespace rednaoeasycalculationforms\Parser\Elements;

use rednaoeasycalculationforms\Parser\Core\ParseBase;
use rednaoeasycalculationforms\Parser\Core\ParseFactory;
use rednaoeasycalculationforms\Parser\Core\ParseUtilities;

class ParseFor extends ParseBase
{
    /** @var ParseBase */
    private $Var;
    /** @var ParseBase */
    public $Cond;
    /** @var ParseBase */
    public $Inc;
    public $ShouldBreak=false;
    /** @var ParseBase */
    private $Statement;

    public function __construct($options, $parent)
    {
        parent::__construct($options, $parent);
        $this->Var=ParseFactory::GetParseElement($options->V,$this);
        if(isset($options->C)&&$options->C!=null)
            $this->Cond=ParseFactory::GetParseElement($options->C,$this);
        if(isset($options->I)&&$options->I!=null)
            $this->Inc=ParseFactory::GetParseElement($options->I,$this);

        $this->Statement=ParseFactory::GetParseElement($options->S,$this);
    }

    public function Parse($type = null)
    {
        $this->ShouldBreak=false;
        $this->Var->Parse();
        $result=null;
        while($this->Cond==null||$this->Cond->Parse())
        {
            $result=$this->Statement->Parse();
            if($this->ShouldBreak)
                return $result;
            if($this->Inc!=null)
                $this->Inc->Parse();
        }
        return $result;
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