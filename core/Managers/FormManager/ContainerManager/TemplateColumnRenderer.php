<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\HTMLParsers\Core\HTMLParserBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Renderers\Core\RendererBase;
use Twig\Markup;

class TemplateColumnRenderer extends ColumnRenderer
{
    public $Label;
    public $Content;
    public function __construct($loader,$label,$content)
    {
        parent::__construct($loader,null,null);
        $this->Label=$label;
        $this->Content=$content;
    }

    public function GetLabel(){
        return $this->Label;
    }

    public function GetValue()
    {
        return new Markup($this->Content,'UTF-8');
    }


    public function GetHtml($context){
        return new Markup($this->Content,'UTF-8');
    }
}