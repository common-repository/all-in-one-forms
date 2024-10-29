<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLSimpleContainer;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\ParserUtilities;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class ImageParser extends HTMLParserBase
{
    public $ImageId;
    public $URL;
    public $Width;
    public $Height;
    public function ParseContent()
    {
        $this->ImageId=$this->GetAttributeValue('id');
        $this->URL=$this->GetAttributeValue('url');
        $this->Width=Sanitizer::SanitizeHTMLSize($this->GetAttributeValue('width'));
        $this->Height=Sanitizer::SanitizeHTMLSize($this->GetAttributeValue('height'));
        return ParserUtilities::MaybeApplyMarks($this);
    }

    public function GetImageURL(){
        return wp_get_attachment_url($this->ImageId);
    }


    public function GetStyles(){
        $style='';
        if($this->Width!='')
            $style.='width:'.$this->Width.';';
        if($this->Height!='')
            $style.='height:'.$this->Height.';';
        return $style;
    }
    protected function GetTemplateName()
    {
        return 'core/Managers/HTMLGenerator/HTMLParsers/ImageParser.twig';
    }
}