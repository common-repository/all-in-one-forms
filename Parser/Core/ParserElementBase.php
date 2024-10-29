<?php

namespace rednaoeasycalculationforms\Parser\Core;

use rednaoeasycalculationforms\Parser\Elements\ParseMain;

abstract class ParserElementBase {
    /** @var ParserElementBase */
    public $Parent;
    public $Data;


    /**
     * ParserElementBase constructor.
     * @param $parent ParserElementBase
     * @param $data
     */
    function __construct($parent,$data) {
        $this->Parent=$parent;
        $this->Data=$data;
    }

    /**
     * @return ParseMain
     */
    public function GetMain()
    {
        if($this->Parent==null)
            return $this;

        return $this->Parent->GetMain();
    }

    public abstract function Parse();

}