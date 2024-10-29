<?php

namespace rednaoeasycalculationforms\core\Managers\TwigManager;

use rednaoeasycalculationforms\core\Loader;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigFunctions extends AbstractExtension
{
    /** @var $Loader Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function getFunctions()
    {
        $me=$this;
        return[
            new TwigFunction('AddStyle',function ($handler,$url,$dependency=array())use($me){
                wp_enqueue_style($handler,$me->Loader->URL.$url,$dependency);
            })
        ];
    }


}