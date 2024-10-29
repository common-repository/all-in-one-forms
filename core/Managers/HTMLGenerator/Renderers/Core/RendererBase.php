<?php

namespace rednaoeasycalculationforms\core\Managers\HTMLGenerator\Renderers\Core;

use rednaoeasycalculationforms\core\Loader;
use Twig\Environment;
use Twig\Markup;

abstract class RendererBase
{
    /** @var Loader */
    public $loader;
    /**
     * RendererBase constructor.
     * @param $twig Environment
     */
    public function __construct($loader)
    {
        $this->loader=$loader;
    }

    protected abstract function GetTemplateName();

    public function Render(){
        return $this->RenderTemplate($this->GetTemplateName(),$this);
    }

    protected abstract function PrepareContent();

    public function RenderTemplate($templateName,$model)
    {
        $this->PrepareContent();
        return new Markup($this->loader->GetTwigManager()->Render($templateName,$model),'UTF-8');
    }

}