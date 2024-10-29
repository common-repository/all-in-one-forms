<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core;

use rednaoeasycalculationforms\Utilities\Sanitizer;
use Twig\Markup;

class HTMLSimpleContainer extends HTMLParserWithChildren
{
    public $TagName;
    public $Classes='';
    public function __construct($formBuilder, $parent, $data,$tagName)
    {
        parent::__construct($formBuilder, $parent, $data);
        $this->TagName=$tagName;
    }

    public function GetClasses(){
        if($this->Classes=='')
            return '';

        return new Markup('class="'.$this->Classes.'"','UTF-8');
    }

    public function GetAlignment(){
        switch (Sanitizer::GetStringValueFromPath($this->Data,['attrs','align']))
        {
            case 'left':
                return 'left';
            case 'right':
                return 'right';
            case 'center':
                return 'center';
            default:
                return '';
        }
    }

    public function GetChildren()
    {
        if($this->Data!=null&&!isset($this->Data->content))
            return new Markup('<br/>','UTF-8');
        return $this->RenderChildren();
    }

    protected function GetTemplateName()
    {
        return 'core/Managers/HTMLGenerator/HTMLParsers/Core/HTMLSimpleContainer.twig';
    }
}