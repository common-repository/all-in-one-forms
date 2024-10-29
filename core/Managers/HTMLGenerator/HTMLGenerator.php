<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator;

use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLContextBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\DocumentParser;
use rednaoeasycalculationforms\js\src\Dynamics\Shared\HTMLGenerator\HTMLLayout\HTMLInlineLayout;
use rednaoeasycalculationforms\js\src\Dynamics\Shared\HTMLGenerator\HTMLLayout\HTMLLayoutBase;
use rednaoeasycalculationforms\Utilities\HtmlTagWrapper;
use rednaoeasycalculationforms\Utilities\ObjectSanitizer;

class HTMLGenerator
{
    /** @var FormBuilder */
    public $FormBuilder;
    public $Options;
    /** @var DocumentParser */
    public $DocumentParser;

    /**
     * @param $formBuilder
     * @param $options
     * @param HTMLContextBase $context
     */
    public function __construct($formBuilder,$options,$context=null)
    {
        $this->FormBuilder=$formBuilder;
        require_once $this->FormBuilder->Loader->DIR.'vendor/autoload.php';
        $this->Options=$options;
        $this->DocumentParser=(new DocumentParser($formBuilder,$options,$context));
    }

    public function SetIsTest(){
        $this->FormBuilder->SetIsTest();
    }



    public function GetInline(){
        return strval($this->DocumentParser->GetInline());
    }


    public function GetHTML(){
        return strval($this->DocumentParser->GetHTML());
    }




}