<?php


namespace rednaoeasycalculationforms\Parser\Core;


abstract class ParseBase
{
    public $Options;
    /** @var ParseBase */
    public $Parent;

    public function __construct($options,$parent)
    {
        $this->Options=$options;
        $this->Parent=$parent;
    }

    /**
     * @return DataRetriever
     */
    public function GetRetriever()
    {
        return  $this->Parent->GetRetriever();
    }

    public function GetType()
    {
        return $this->Options->T;
    }

    public abstract function Parse($type=null);

}