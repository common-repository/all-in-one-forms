<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core;

use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\DocumentParser;
use rednaoeasycalculationforms\core\Managers\TwigManager\TwigManager;
use rednaoeasycalculationforms\Utilities\Sanitizer;
use Twig\Environment;
use Twig\Markup;

abstract class HTMLParserBase
{
    /** @var FormBuilder */
    public $FormBuilder;
    /** @var HTMLParserBase */
    public $Parent;
    public $Data;

    public function __construct($formBuilder,$parent,$data)
    {
        $this->FormBuilder=$formBuilder;
        $this->Parent=$parent;
        $this->Data=$data;
    }

    /**
     * @return DocumentParser;
     */
    public function GetDocument(){
        if($this->Parent==null)
            return $this;

        return $this->Parent->GetDocument();
    }

    public function GetLoader(){
        return $this->FormBuilder->Loader;
    }

    /**
     * @return HTMLParserBase
     */
    public abstract function ParseContent();

    public function GetAttributeValue($attributeName,$defaultValue=null)
    {
        return Sanitizer::GetValueFromPath($this->Data,['attrs',$attributeName],$defaultValue);

    }

    public function GetStringAttributeValue($attributeName,$defaultValue='')
    {
        return Sanitizer::SanitizeString($this->GetAttributeValue($attributeName,$defaultValue));
    }

    public function GetNumericAttributeValue($attributeName,$defaultValue=0)
    {
        return Sanitizer::SanitizeNumber($this->GetAttributeValue($attributeName,$defaultValue));
    }


    protected abstract function GetTemplateName();

    public function Render(){
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }

    public function RenderTemplate($templateName,$model)
    {
        $markup= new Markup($this->FormBuilder->Loader->GetTwigManager()->Render($templateName,$model),"UTF-8");
        return $markup;
    }

}