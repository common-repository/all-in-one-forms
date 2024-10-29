<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLContextBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserWithChildren;
use Twig\Environment;
use Twig\Extra\CssInliner\CssInlinerExtension;

class DocumentParser extends HTMLParserWithChildren
{

    /** @var Formbuilder */
    public $FormBuilder;
    /** @var HTMLContextBase */
    public $Context;

    public function __construct($formBuilder, $data,$context)
    {
        parent::__construct($formBuilder, null, $data);
        $this->Context=$context;
        $this->FormBuilder=$formBuilder;
    }

    public function GetCustomStyles(){
        return apply_filters('allinoneforms_get_custom_styles','',$this->FormBuilder);
    }

    public function GetInline(){
        $this->ParseContent();
        return $this->Render();
    }
    public function GetHTML(){
        $this->ParseContent();
        return $this->RenderTemplate('core/Managers/HTMLGenerator/HTMLParsers/DocumentParser.HTML.twig',$this);
    }

    protected function GetTemplateName()
    {
        return 'core/Managers/HTMLGenerator/HTMLParsers/DocumentParser.Inline.twig';
    }
}