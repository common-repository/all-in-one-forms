<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLSimpleContainer;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\ParserUtilities;

class TextParser extends HTMLParserBase
{
    public $Text;
    public function ParseContent()
    {

        $this->Text=$this->Data->text;
        return ParserUtilities::MaybeApplyMarks($this);
    }

    public function Render()
    {
        return esc_html($this->Text);
    }

    protected function GetTemplateName()
    {
        // TODO: Implement GetTemplateName() method.
    }
}