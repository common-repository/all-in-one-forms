<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use Twig\Markup;

class HorizontalRulerParser extends HTMLParserBase
{

    public function ParseContent()
    {
        return $this;
    }

    public function Render()
    {
        return new Markup('<hr/>','UTF-8');
    }

    protected function GetTemplateName()
    {
        // TODO: Implement GetTemplateName() method.
    }
}